<?php

namespace App\Http\Controllers;

use App\Models\RobotsSetting;
use Illuminate\Support\Facades\Cache;

class RobotsController extends Controller
{
    /**
     * Generate the robots.txt output dynamically.
     */
    public function __invoke()
    {
        $settings = Cache::remember('robots_txt_settings', now()->addDay(), function () {
            return RobotsSetting::firstOrCreate(['id' => 1], RobotsSetting::defaults());
        });

        $content = $this->generateRobotsTxt($settings);

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * Build the robots.txt content according to settings.
     */
    protected function generateRobotsTxt(RobotsSetting $settings): string
    {
        $mode = $settings->crawling_mode;

        // If maintenance or development mode, block everything
        if ($mode === 'maintenance' || $mode === 'development') {
            return "User-agent: *\nDisallow: /";
        }

        // Under custom mode, if frontend crawling is disabled, block everything
        if ($mode === 'custom' && !$settings->allow_frontend) {
            return "User-agent: *\nDisallow: /";
        }

        // Otherwise, allow crawling and append specific disallow rules
        $rules = [];
        $rules[] = "User-agent: *";
        $rules[] = "Allow: /";

        // Block Backend / Admin
        if ($settings->block_backend) {
            $rules[] = "Disallow: /admin/";
            $rules[] = "Disallow: /sortiqadmin/";
        }

        // Block API
        if ($settings->block_api) {
            $rules[] = "Disallow: /api/";
        }

        // Block Login / Auth
        if ($settings->block_login) {
            $rules[] = "Disallow: /login";
            $rules[] = "Disallow: /logout";
            $rules[] = "Disallow: /register";
            $rules[] = "Disallow: /password/";
        }

        // Block Images
        if ($settings->block_images) {
            $rules[] = "Disallow: /images/";
            $rules[] = "Disallow: /storage/";
        }

        // Block CSS
        if ($settings->block_css) {
            $rules[] = "Disallow: /css/";
            $rules[] = "Disallow: /build/assets/*.css";
        }

        // Block JS
        if ($settings->block_js) {
            $rules[] = "Disallow: /js/";
            $rules[] = "Disallow: /build/assets/*.js";
        }

        // Custom Rules
        if ($settings->custom_rules) {
            $customLines = explode("\n", str_replace("\r", "", $settings->custom_rules));
            foreach ($customLines as $line) {
                $line = trim($line);
                if ($line !== '') {
                    if (preg_match('/^(Disallow|Allow|Sitemap|User-agent):/i', $line)) {
                        $rules[] = $line;
                    } else {
                        $rules[] = "Disallow: " . $line;
                    }
                }
            }
        }

        // Include Sitemap
        if ($settings->include_sitemap) {
            $siteUrl = rtrim(url('/'), '/');
            $rules[] = "Sitemap: {$siteUrl}/sitemap.xml";
        }

        return implode("\n", $rules);
    }
}
