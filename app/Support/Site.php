<?php

namespace App\Support;

use App\Services\SettingService;
use Illuminate\Support\Str;

/**
 * Presentador de los datos públicos del despacho (nombre, contacto, redes, SEO).
 * Las vistas leen de aquí en lugar de repetir claves de configuración.
 */
class Site
{
    public function get(string $key, mixed $default = null): mixed
    {
        return SettingService::get($key, $default);
    }

    public function name(): string
    {
        return (string) $this->get('firm_name', config('app.name'));
    }

    public function tagline(): string
    {
        return (string) $this->get('firm_tagline', 'Estrategia jurídica para decisiones que importan.');
    }

    public function description(): string
    {
        return (string) $this->get('firm_description', 'Despacho jurídico en Ciudad de México.');
    }

    public function phone(): string
    {
        return (string) $this->get('phone', '');
    }

    public function phoneHref(): string
    {
        return 'tel:+'.ltrim(digits($this->phone()), '+');
    }

    public function email(): string
    {
        return (string) $this->get('email', '');
    }

    public function leadsEmail(): ?string
    {
        return config('despacho.notify_email') ?: ($this->email() ?: null);
    }

    public function whatsappUrl(?string $message = null): ?string
    {
        $number = digits((string) $this->get('whatsapp_number', ''));
        if ($number === '') {
            return null;
        }
        $text = $message ?? (string) $this->get('whatsapp_message', 'Hola, me gustaría solicitar información para una consulta jurídica.');

        return 'https://wa.me/'.$number.'?text='.rawurlencode($text);
    }

    /**
     * @return array<int, string>
     */
    public function addressLines(): array
    {
        return array_values(array_filter([
            $this->get('address_line1'),
            $this->get('address_line2'),
            trim($this->get('city', 'Ciudad de México').', '.$this->get('country', 'México')),
            $this->get('postal_code') ? 'C.P. '.$this->get('postal_code') : null,
        ]));
    }

    public function hours(): string
    {
        return (string) $this->get('hours', 'Lunes a viernes · 9:00 a 18:00');
    }

    public function mapsUrl(): string
    {
        $query = $this->get('maps_query') ?: implode(', ', array_slice($this->addressLines(), 0, 3));

        return 'https://www.google.com/maps/dir/?api=1&destination='.rawurlencode((string) $query);
    }

    public function mapsEmbedUrl(): ?string
    {
        $url = (string) $this->get('maps_embed_url', '');

        return Str::startsWith($url, ['https://www.google.com/maps/embed', 'https://www.openstreetmap.org/export/embed']) ? $url : null;
    }

    /**
     * @return array<int, array{name: string, icon: string, url: string}>
     */
    public function social(): array
    {
        $networks = [
            'linkedin' => 'LinkedIn',
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'x_url' => 'X',
            'youtube' => 'YouTube',
        ];

        $items = [];
        foreach ($networks as $key => $name) {
            $url = (string) $this->get($key, '');
            if (Str::startsWith($url, ['https://', 'http://'])) {
                $items[] = ['name' => $name, 'icon' => $key === 'x_url' ? 'x' : $key, 'url' => $url];
            }
        }

        return $items;
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    public function stats(): array
    {
        $stats = $this->get('stats');

        return is_array($stats) && $stats !== [] ? $stats : config('despacho.stats');
    }

    public function isPlaceholder(?string $value): bool
    {
        return $value === null || $value === '' || Str::contains($value, ['[', ']']);
    }

    /**
     * Imagen de una vista (ruta relativa a public/ definida en config/despacho.php).
     */
    public function image(string $key, ?string $fallback = null): string
    {
        return asset(config("despacho.images.{$key}", $fallback ?? config('despacho.images.hero')));
    }

    /**
     * @return array<int, array{label: string, route: string, match: string}>
     */
    public function navigation(): array
    {
        return [
            ['label' => 'Inicio', 'route' => 'home', 'match' => 'home'],
            ['label' => 'Nosotros', 'route' => 'nosotros', 'match' => 'nosotros'],
            ['label' => 'Áreas de práctica', 'route' => 'areas.index', 'match' => 'areas.*'],
            ['label' => 'Equipo', 'route' => 'team', 'match' => 'team*'],
            ['label' => 'Experiencia', 'route' => 'experience', 'match' => 'experience'],
            ['label' => 'Insights', 'route' => 'articles.index', 'match' => 'articles.*'],
            ['label' => 'Herramientas', 'route' => 'tools.severance', 'match' => 'tools.*'],
            ['label' => 'Contacto', 'route' => 'contact.form', 'match' => 'contact.*'],
        ];
    }
}
