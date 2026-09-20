<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Attorney extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'slug',
        'name',
        'position',
        'bio_short',
        'bio',
        'education',
        'credentials',
        'experience',
        'memberships',
        'languages',
        'areas',
        'linkedin',
        'is_demo',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'bio' => 'array',
            'education' => 'array',
            'experience' => 'array',
            'memberships' => 'array',
            'languages' => 'array',
            'areas' => 'array',
            'is_demo' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
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

    public function photoUrl(): string
    {
        return $this->getFirstMediaUrl('avatar') ?: asset('img/portrait-'.((($this->getKey() ?? 0) % 3) + 1).'.svg');
    }

    public function initials(): string
    {
        return Str::of($this->name)->replace(['[', ']'], '')->explode(' ')->filter()->take(2)
            ->map(fn (string $part): string => Str::upper(Str::substr($part, 0, 1)))->implode('');
    }

    /**
     * Tarjeta de contacto (vCard 3.0) descargable.
     */
    public function vcard(): string
    {
        $site = site();
        $lines = [
            'BEGIN:VCARD',
            'VERSION:3.0',
            'FN:'.$this->escapeVcard($this->name),
            'ORG:'.$this->escapeVcard($site->name()),
            'TITLE:'.$this->escapeVcard($this->position),
        ];
        if ($site->phone() !== '') {
            $lines[] = 'TEL;TYPE=WORK,VOICE:'.$this->escapeVcard($site->phone());
        }
        if ($site->email() !== '') {
            $lines[] = 'EMAIL;TYPE=WORK:'.$this->escapeVcard($site->email());
        }
        $lines[] = 'URL:'.route('team.show', $this);
        $lines[] = 'END:VCARD';

        return implode("\r\n", $lines)."\r\n";
    }

    private function escapeVcard(string $value): string
    {
        return str_replace([',', ';', "\n"], ['\\,', '\\;', '\\n'], $value);
    }
}
