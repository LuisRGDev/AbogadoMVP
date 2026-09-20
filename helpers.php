<?php

use App\Services\HtmlSanitizer;
use App\Services\SettingService;
use App\Support\Site;
use Illuminate\Support\HtmlString;

if (! function_exists('site')) {
    function site(): Site
    {
        return app(Site::class);
    }
}

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return SettingService::get($key, $default);
    }
}

if (! function_exists('digits')) {
    function digits(string $value): string
    {
        return preg_replace('/\D/', '', $value) ?? '';
    }
}

if (! function_exists('whatsapp_url')) {
    function whatsapp_url(?string $message = null): string
    {
        return site()->whatsappUrl($message) ?? '#';
    }
}

if (! function_exists('format_date')) {
    function format_date(mixed $date, string $format = 'D [de] MMMM [de] YYYY'): string
    {
        if (! $date) {
            return '';
        }
        $date = is_string($date) ? new Carbon\Carbon($date) : $date;

        return $date->locale('es_MX')->isoFormat($format);
    }
}

if (! function_exists('em')) {
    /**
     * Texto plano con énfasis: *palabra* → <em>palabra</em>, y saltos de línea → <br>.
     * Escapa todo el contenido, por lo que es seguro para texto editable desde el panel.
     */
    function em(?string $text, bool $breaks = false): HtmlString
    {
        $escaped = e((string) $text);
        $escaped = preg_replace('/\*([^*]+)\*/u', '<em>$1</em>', $escaped) ?? $escaped;
        if ($breaks) {
            $escaped = nl2br($escaped);
        }

        return new HtmlString($escaped);
    }
}

if (! function_exists('plain')) {
    /** Quita la marca de énfasis (*) para usar el texto en <title> y metadatos. */
    function plain(?string $text): string
    {
        return trim(preg_replace('/\s+/u', ' ', str_replace('*', '', (string) $text)) ?? '');
    }
}

if (! function_exists('clean_html')) {
    function clean_html(?string $html): HtmlString
    {
        return new HtmlString(app(HtmlSanitizer::class)->clean((string) $html));
    }
}

if (! function_exists('breadcrumb_schema')) {
    /**
     * @param  array<int, array{0: string, 1: string}>  $items  [[nombre, url], ...]
     * @return array<string, mixed>
     */
    function breadcrumb_schema(array $items): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->values()->map(fn (array $item, int $i): array => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'name' => $item[0],
                'item' => $item[1],
            ])->all(),
        ];
    }
}
