<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Article extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'body',
        'category',
        'date',
        'read_time',
        'seo_title',
        'seo_description',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_published' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml']);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->whereDate('date', '<=', now());
    }

    public function scopeInCategory(Builder $query, ?string $category): Builder
    {
        return $category ? $query->where('category', $category) : $query;
    }

    public function scopeMatching(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }
        $like = '%'.addcslashes($term, '%_\\').'%';

        return $query->where(fn (Builder $q) => $q->where('title', 'like', $like)->orWhere('excerpt', 'like', $like)->orWhere('body', 'like', $like));
    }

    public function coverUrl(): string
    {
        return $this->getFirstMediaUrl('featured') ?: asset('img/article-'.((($this->getKey() ?? 0) % 3) + 1).'.svg');
    }

    public function hasCustomCover(): bool
    {
        return $this->hasMedia('featured');
    }

    public function readingMinutes(): int
    {
        $words = str_word_count(strip_tags((string) $this->body), 0, 'áéíóúüñÁÉÍÓÚÜÑ');

        return max(1, (int) ceil($words / 200));
    }

    public function readTimeLabel(): string
    {
        return $this->read_time ?: $this->readingMinutes().' min';
    }

    /**
     * Artículos relacionados: primero de la misma categoría, luego los más recientes.
     *
     * @return Collection<int, Article>
     */
    public function related(int $limit = 3): Collection
    {
        return static::published()
            ->whereKeyNot($this->getKey())
            ->orderByRaw('category = ? desc', [$this->category])
            ->latest('date')
            ->limit($limit)
            ->get();
    }
}
