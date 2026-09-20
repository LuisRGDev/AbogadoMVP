<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\SiteSettings;
use App\Filament\Resources\AttorneyResource;
use App\Filament\Resources\ExperienceCaseResource;
use App\Filament\Resources\PageResource;
use App\Models\Attorney;
use App\Models\ExperienceCase;
use App\Models\Page;
use App\Models\Testimonial;
use Filament\Widgets\Widget;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LaunchChecklist extends Widget
{
    protected static ?int $sort = 0;

    protected static string $view = 'filament.widgets.launch-checklist';

    protected int|string|array $columnSpan = 'full';

    /**
     * Tareas que suelen quedar pendientes antes de publicar el sitio.
     *
     * @return array<int, array{label: string, hint: string, done: bool, url: string}>
     */
    public function getChecklist(): array
    {
        $site = site();
        $settings = SiteSettings::getUrl();

        return [
            ['label' => 'Nombre del despacho', 'hint' => 'Sustituya el marcador [NOMBRE DEL DESPACHO].', 'done' => ! $site->isPlaceholder($site->name()), 'url' => $settings],
            ['label' => 'Teléfono y correo reales', 'hint' => 'Los actuales son de ejemplo.', 'done' => ! $site->isPlaceholder($site->phone()) && $site->phone() !== '+52 55 0000 0000' && ! str_ends_with($site->email(), '.example'), 'url' => $settings],
            ['label' => 'Dirección de la oficina', 'hint' => 'Calle, colonia y código postal.', 'done' => ! $site->isPlaceholder(implode(' ', $site->addressLines())) && setting('postal_code') !== '00000', 'url' => $settings],
            ['label' => 'WhatsApp y redes sociales', 'hint' => 'Número de WhatsApp y al menos una red social.', 'done' => filled(setting('whatsapp_number')) && setting('whatsapp_number') !== '+52 55 0000 0000' && $site->social() !== [], 'url' => $settings],
            ['label' => 'Perfiles de abogados reales', 'hint' => 'Hay perfiles de demostración o con marcadores.', 'done' => Attorney::active()->count() > 0 && ! Attorney::active()->where('is_demo', true)->exists(), 'url' => AttorneyResource::getUrl()],
            ['label' => 'Fotografías del equipo', 'hint' => 'Suba un retrato para cada abogado.', 'done' => Attorney::active()->count() > 0 && Media::where('model_type', Attorney::class)->where('collection_name', 'avatar')->count() >= Attorney::active()->count(), 'url' => AttorneyResource::getUrl()],
            ['label' => 'Casos y testimonios reales', 'hint' => 'Publique solo contenido autorizado por sus clientes.', 'done' => ExperienceCase::active()->count() > 0 && ! ExperienceCase::active()->where('is_demo', true)->exists() && ! Testimonial::active()->where('is_demo', true)->exists(), 'url' => ExperienceCaseResource::getUrl()],
            ['label' => 'Textos legales revisados', 'hint' => 'Aviso de privacidad, términos y disclaimer aún tienen texto de ejemplo.', 'done' => ! Page::whereIn('slug', ['privacidad', 'terminos', 'disclaimer'])->where('content', 'like', '%[%')->exists(), 'url' => PageResource::getUrl()],
            ['label' => 'Correo de notificaciones configurado', 'hint' => 'Configure MAIL_* en el servidor para recibir avisos de nuevas solicitudes.', 'done' => ! in_array(config('mail.default'), ['log', 'array'], true), 'url' => $settings],
            ['label' => 'Modo producción activo', 'hint' => 'APP_ENV=production y APP_DEBUG=false en el servidor.', 'done' => app()->isProduction() && ! config('app.debug'), 'url' => $settings],
        ];
    }

    public function getProgress(): int
    {
        $items = $this->getChecklist();

        return (int) round(count(array_filter($items, fn (array $item): bool => $item['done'])) / count($items) * 100);
    }
}
