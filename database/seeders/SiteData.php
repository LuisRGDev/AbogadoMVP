<?php

namespace Database\Seeders;

use Illuminate\Support\Arr;

/**
 * Contenido editorial de partida (database/seeders/data/site.json).
 * Es texto de demostración con marcadores [entre corchetes] que el despacho debe sustituir.
 */
class SiteData
{
    /** @var array<string, mixed>|null */
    private static ?array $data = null;

    /**
     * @return array<string, mixed>
     */
    public static function all(): array
    {
        return self::$data ??= json_decode((string) file_get_contents(__DIR__.'/data/site.json'), true, flags: JSON_THROW_ON_ERROR);
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return Arr::get(self::all(), $key, $default);
    }

    /**
     * @param  array<int, array{0: string, 1: string}>  $sections
     */
    public static function sectionsToHtml(array $sections): string
    {
        $html = '';
        foreach ($sections as [$heading, $text]) {
            $html .= ($heading !== '' ? '<h2>'.e($heading).'</h2>' : '').'<p>'.e($text).'</p>';
        }

        return $html;
    }
}
