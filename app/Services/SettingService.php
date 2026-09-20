<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    private const CACHE_KEY = 'settings.all';

    /**
     * Todos los ajustes en una sola consulta (cacheada hasta que se edite alguno).
     *
     * @return array<string, mixed>
     */
    public static function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function (): array {
            return Setting::query()->get()
                ->mapWithKeys(fn (Setting $setting): array => [$setting->key => $setting->value])
                ->all();
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = static::all()[$key] ?? null;

        return $value === null || $value === '' ? $default : $value;
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
