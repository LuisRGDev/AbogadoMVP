<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PracticeArea extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'slug',
        'num',
        'icon',
        'title',
        'short',
        'overview',
        'matters',
        'needs',
        'process',
        'faqs',
        'seo_title',
        'seo_description',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'matters' => 'array',
            'needs' => 'array',
            'process' => 'array',
            'faqs' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml']);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Portada del área: la subida en el panel, la ilustración propia (img/area-{slug}.svg)
     * o una de respaldo rotativa, de modo que ninguna área comparta la misma imagen.
     */
    public function coverUrl(): string
    {
        if ($uploaded = $this->getFirstMediaUrl('cover')) {
            return $uploaded;
        }

        $own = 'img/area-'.$this->slug.'.svg';
        if (is_file(public_path($own))) {
            return asset($own);
        }

        $fallbacks = config('despacho.images.area_fallbacks');

        return asset($fallbacks[($this->getKey() ?? 0) % count($fallbacks)]);
    }

    public function hasCustomCover(): bool
    {
        return $this->hasMedia('cover') || is_file(public_path('img/area-'.$this->slug.'.svg'));
    }

    /**
     * Pasos del proceso normalizados a [{title, description}].
     *
     * @return array<int, array{title: string, description: string}>
     */
    public function processSteps(): array
    {
        return collect($this->process ?? [])->map(function (mixed $step): array {
            if (is_array($step) && array_is_list($step)) {
                return ['title' => (string) ($step[0] ?? ''), 'description' => (string) ($step[1] ?? '')];
            }

            return ['title' => (string) ($step['title'] ?? ''), 'description' => (string) ($step['description'] ?? '')];
        })->all();
    }

    /**
     * Preguntas frecuentes normalizadas a [{question, answer}].
     *
     * @return array<int, array{question: string, answer: string}>
     */
    public function faqItems(): array
    {
        return collect($this->faqs ?? [])->map(function (mixed $faq): array {
            if (is_array($faq) && array_is_list($faq)) {
                return ['question' => (string) ($faq[0] ?? ''), 'answer' => (string) ($faq[1] ?? '')];
            }

            return ['question' => (string) ($faq['question'] ?? ''), 'answer' => (string) ($faq['answer'] ?? '')];
        })->filter(fn (array $faq): bool => $faq['question'] !== '')->values()->all();
    }
}
