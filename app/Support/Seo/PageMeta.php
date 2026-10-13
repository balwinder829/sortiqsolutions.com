<?php

namespace App\Support\Seo;

use App\Models\SiteLayoutSetting;
use Illuminate\Support\Str;
use Throwable;

class PageMeta
{
    private static ?array $resolvedMetaMap = null;

    public function __construct(
        public readonly ?string $title,
        public readonly string $description,
        public readonly string $image,
        public readonly ?string $keywords = null,
        public readonly string $type = 'website',
    ) {
    }

    public static function forRoute(?string $routeName): self
    {
        return new self(
            title: self::titleForRoute($routeName),
            description: self::descriptionForRoute($routeName),
            image: asset(config('seo.default_image')),
            keywords: self::keywordsForRoute($routeName),
        );
    }

    public static function titleForRoute(?string $routeName): ?string
    {
        if (! $routeName) {
            return null;
        }

        $title = trim((string) data_get(self::metaForRoute($routeName), 'title'));

        return $title !== '' ? $title : null;
    }

    public static function keywordsForRoute(?string $routeName): ?string
    {
        if (! $routeName) {
            return null;
        }

        $keywords = trim((string) data_get(self::metaForRoute($routeName), 'keywords'));

        return $keywords !== '' ? $keywords : null;
    }

    public static function descriptionForRoute(?string $routeName): string
    {
        if (! $routeName) {
            return config('seo.default_description');
        }

        $description = trim((string) data_get(self::metaForRoute($routeName), 'description'));

        return $description !== '' ? $description : config('seo.default_description');
    }

    public static function custom(string $description, ?string $image = null, string $type = 'website', ?string $title = null, ?string $keywords = null): self
    {
        return new self(
            title: $title,
            description: trim($description),
            image: $image ?: asset(config('seo.default_image')),
            keywords: $keywords,
            type: $type,
        );
    }

    public static function article(?string $text, ?string $image = null, ?string $title = null, ?string $keywords = null): self
    {
        return new self(
            title: $title,
            description: Str::limit(strip_tags((string) $text), 155),
            image: $image ?: asset(config('seo.default_image')),
            keywords: $keywords,
            type: 'article',
        );
    }

    private static ?array $draftRoutesCache = null;

    public static function isDraftRoute(?string $routeName, ?string $path = null): bool
    {
        if (! $routeName && ! $path) {
            return false;
        }

        if (self::$draftRoutesCache === null) {
            self::$draftRoutesCache = self::loadDraftMap();
        }

        $drafts = self::$draftRoutesCache;

        if ($routeName && in_array($routeName, $drafts['routes'], true)) {
            return true;
        }

        if ($path) {
            $cleanPath = '/' . trim($path, '/');
            if (in_array($cleanPath, $drafts['paths'], true)) {
                return true;
            }
        }

        return false;
    }

    private static function loadDraftMap(): array
    {
        $draftRoutes = [];
        $draftPaths = [];

        try {
            $setting = SiteLayoutSetting::query()
                ->where('key', SiteLayoutSetting::MAIN_KEY)
                ->first();

            $data = is_array($setting?->data) ? $setting->data : [];
            $pageMeta = $data['page_meta'] ?? [];
            $customPages = $data['custom_pages'] ?? [];

            foreach ($pageMeta as $rName => $meta) {
                if (is_array($meta) && ($meta['status'] ?? 'published') === 'draft') {
                    $draftRoutes[] = $rName;
                    if (!empty($meta['custom_path'])) {
                        $draftPaths[] = '/' . trim($meta['custom_path'], '/');
                    }
                    try {
                        $defPath = SeoPageCatalog::defaultPathForRoute($rName);
                        if (!empty($defPath)) {
                            $draftPaths[] = '/' . trim($defPath, '/');
                        }
                    } catch (\Throwable $e) {}
                }
            }

            foreach ($customPages as $rName => $cp) {
                if (is_array($cp) && ($cp['status'] ?? 'published') === 'draft') {
                    $draftRoutes[] = $rName;
                    if (!empty($cp['slug'])) {
                        $draftPaths[] = '/' . trim($cp['slug'], '/');
                    }
                }
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('pages')) {
                $dbDrafts = \App\Models\Page::where('status', 'draft')->get(['route_name', 'url_path']);
                foreach ($dbDrafts as $p) {
                    if (!empty($p->route_name)) {
                        $draftRoutes[] = $p->route_name;
                        try {
                            $defPath = SeoPageCatalog::defaultPathForRoute($p->route_name);
                            if (!empty($defPath)) {
                                $draftPaths[] = '/' . trim($defPath, '/');
                            }
                        } catch (\Throwable $e) {}
                    }
                    if (!empty($p->url_path)) {
                        $draftPaths[] = '/' . trim($p->url_path, '/');
                    }
                }
            }
        } catch (\Throwable $e) {
            // Fail open on error
        }

        return [
            'routes' => array_values(array_unique($draftRoutes)),
            'paths' => array_values(array_unique($draftPaths)),
        ];
    }

    public static function clearCache(): void
    {
        self::$resolvedMetaMap = null;
        self::$draftRoutesCache = null;
    }

    private static function metaForRoute(string $routeName): array
    {
        return self::resolvedMetaMap()[$routeName] ?? [];
    }

    private static function resolvedMetaMap(): array
    {
        if (self::$resolvedMetaMap !== null) {
            return self::$resolvedMetaMap;
        }

        $metaMap = SeoPageCatalog::defaultMetaMap();

        try {
            $setting = SiteLayoutSetting::query()
                ->where('key', SiteLayoutSetting::MAIN_KEY)
                ->first();

            $overrides = is_array($setting?->data)
                ? ($setting->data['page_meta'] ?? [])
                : [];
        } catch (Throwable $exception) {
            $overrides = [];
        }

        foreach ($overrides as $routeName => $meta) {
            if (! is_array($meta) || ! array_key_exists($routeName, $metaMap)) {
                continue;
            }

            $title = trim((string) ($meta['title'] ?? ''));
            $description = trim((string) ($meta['description'] ?? ''));
            $keywords = trim((string) ($meta['keywords'] ?? ''));

            if ($title !== '') {
                $metaMap[$routeName]['title'] = $title;
            }

            if ($description !== '') {
                $metaMap[$routeName]['description'] = $description;
            }

            if ($keywords !== '') {
                $metaMap[$routeName]['keywords'] = $keywords;
            }
        }

        return self::$resolvedMetaMap = $metaMap;
    }
}
