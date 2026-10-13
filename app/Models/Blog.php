<?php

namespace App\Models;

use App\Support\LocalMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public const CATEGORIES = [
        'Company',
        'Web Design',
        'Development',
        'Digital Marketing',
        'Technology',
        'News',
    ];

    protected $fillable = [
        'title',
        'slug',
        'image',
        'category',
        'tags',
        'excerpt',
        'content',
        'published_at',
        'status',
        'views',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'views' => 'integer',
            'tags' => 'array',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? $this->getRouteKeyName(), $value)
            ->orWhere('id', $value)
            ->firstOrFail();
    }

    public function scopePublished(Builder $query): Builder
    {
        $query->where('status', 'published');

        return $query;
    }

    public function scopeOrdered(Builder $query): Builder
    {
        $query->orderBy('published_at', 'desc');
        $query->orderBy('id', 'desc');

        return $query;
    }

    public function getImageUrlAttribute(): ?string
    {
        return LocalMedia::url($this->image) ?: (str_starts_with((string) $this->image, 'http') ? $this->image : null);
    }

    public function getPublishedLabelAttribute(): string
    {
        if ($this->published_at) {
            return $this->published_at->format('Y/m/d \a\t g:i a');
        }

        return 'Not published';
    }
}
