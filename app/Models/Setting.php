<?php

namespace App\Models;

use App\Services\SettingService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => SettingService::flush());
        static::deleted(fn () => SettingService::flush());
    }

    public function getValueAttribute(?string $value): mixed
    {
        return match ($this->type) {
            'boolean' => (bool) $value,
            'json' => $value === null ? null : json_decode($value, true),
            default => $value,
        };
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return SettingService::get($key, $default);
    }

    public static function set(string $key, mixed $value, string $type = 'text', string $group = 'general'): static
    {
        $valueToStore = $type === 'json' ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $valueToStore, 'type' => $type, 'group' => $group]
        );
    }
}
