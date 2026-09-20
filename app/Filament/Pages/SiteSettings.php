<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

/**
 * @property Form $form
 */
class SiteSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Sitio';

    protected static ?string $navigationLabel = 'Configuración del sitio';

    protected static ?string $title = 'Configuración del sitio';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.site-settings';

    /** @var array<string, mixed> */
    public ?array $data = [];

    /**
     * Clave => [tipo, grupo]. Todo lo que se edita aquí se guarda en la tabla settings.
     *
     * @var array<string, array{0: string, 1: string}>
     */
    private const FIELDS = [
        'firm_name' => ['text', 'general'], 'firm_tagline' => ['text', 'general'], 'firm_description' => ['textarea', 'general'],
        'phone' => ['text', 'contact'], 'email' => ['text', 'contact'], 'whatsapp_number' => ['text', 'contact'], 'whatsapp_message' => ['textarea', 'contact'],
        'address_line1' => ['text', 'contact'], 'address_line2' => ['text', 'contact'], 'city' => ['text', 'contact'], 'state' => ['text', 'contact'],
        'postal_code' => ['text', 'contact'], 'country' => ['text', 'contact'], 'hours' => ['text', 'contact'], 'maps_query' => ['text', 'contact'], 'maps_embed_url' => ['text', 'contact'],
        'linkedin' => ['text', 'social'], 'facebook' => ['text', 'social'], 'instagram' => ['text', 'social'], 'x_url' => ['text', 'social'], 'youtube' => ['text', 'social'],
        'hero_eyebrow' => ['text', 'home'], 'hero_title' => ['textarea', 'home'], 'hero_text' => ['textarea', 'home'], 'hero_primary_cta' => ['text', 'home'], 'hero_secondary_cta' => ['text', 'home'],
        'about_title' => ['text', 'home'], 'about_text' => ['textarea', 'home'], 'cta_title' => ['text', 'home'], 'cta_text' => ['textarea', 'home'], 'stats' => ['json', 'home'],
        'seo_title' => ['text', 'seo'], 'seo_description' => ['textarea', 'seo'],
        'calculator_min_wage' => ['text', 'tools'], 'calculator_min_wage_border' => ['text', 'tools'],
    ];

    public function mount(): void
    {
        $this->form->fill(collect(self::FIELDS)->mapWithKeys(fn (array $meta, string $key): array => [$key => setting($key)])->all());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Ajustes')->tabs([
                    Forms\Components\Tabs\Tab::make('Despacho')->icon('heroicon-o-building-library')->schema([
                        Forms\Components\TextInput::make('firm_name')->label('Nombre del despacho')->required()->maxLength(120)->helperText('Aparece en el encabezado, el pie, los correos y el título de cada página.'),
                        Forms\Components\TextInput::make('firm_tagline')->label('Lema')->maxLength(160),
                        Forms\Components\Textarea::make('firm_description')->label('Descripción breve')->rows(3)->maxLength(300)->helperText('Pie de página y datos estructurados para buscadores.'),
                    ]),
                    Forms\Components\Tabs\Tab::make('Contacto')->icon('heroicon-o-phone')->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('phone')->label('Teléfono')->tel()->maxLength(40),
                            Forms\Components\TextInput::make('email')->label('Correo público')->email()->maxLength(190)->helperText('Se muestra en el sitio y recibe las solicitudes (salvo que se defina LEADS_NOTIFY_EMAIL).'),
                            Forms\Components\TextInput::make('whatsapp_number')->label('Número de WhatsApp')->tel()->maxLength(40)->helperText('Con lada internacional, ej. +52 55 1234 5678. Vacío = se oculta el botón.'),
                            Forms\Components\TextInput::make('hours')->label('Horario de atención')->maxLength(120),
                        ]),
                        Forms\Components\Textarea::make('whatsapp_message')->label('Mensaje predefinido de WhatsApp')->rows(2)->maxLength(300),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('address_line1')->label('Calle y número')->maxLength(160),
                            Forms\Components\TextInput::make('address_line2')->label('Colonia y alcaldía')->maxLength(160),
                            Forms\Components\TextInput::make('city')->label('Ciudad')->maxLength(80),
                            Forms\Components\TextInput::make('state')->label('Estado')->maxLength(80),
                            Forms\Components\TextInput::make('postal_code')->label('Código postal')->maxLength(10),
                            Forms\Components\TextInput::make('country')->label('País')->maxLength(80),
                        ]),
                        Forms\Components\TextInput::make('maps_query')->label('Dirección para «Cómo llegar»')->maxLength(200)->helperText('Texto que se enviará a Google Maps.'),
                        Forms\Components\TextInput::make('maps_embed_url')->label('Enlace de mapa incrustado (opcional)')->url()->maxLength(500)->helperText('Google Maps → Compartir → Incorporar un mapa → copie solo la dirección src="…" (https://www.google.com/maps/embed?…).'),
                    ]),
                    Forms\Components\Tabs\Tab::make('Redes sociales')->icon('heroicon-o-share')->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('linkedin')->label('LinkedIn')->url()->maxLength(255),
                            Forms\Components\TextInput::make('facebook')->label('Facebook')->url()->maxLength(255),
                            Forms\Components\TextInput::make('instagram')->label('Instagram')->url()->maxLength(255),
                            Forms\Components\TextInput::make('x_url')->label('X (Twitter)')->url()->maxLength(255),
                            Forms\Components\TextInput::make('youtube')->label('YouTube')->url()->maxLength(255),
                        ]),
                    ]),
                    Forms\Components\Tabs\Tab::make('Página de inicio')->icon('heroicon-o-home')->schema([
                        Forms\Components\TextInput::make('hero_eyebrow')->label('Etiqueta superior')->maxLength(120),
                        Forms\Components\Textarea::make('hero_title')->label('Título principal')->rows(3)->maxLength(200)->helperText('Una línea por renglón. Resalte palabras en dorado con asteriscos: *importan.*'),
                        Forms\Components\Textarea::make('hero_text')->label('Texto introductorio')->rows(3)->maxLength(300),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('hero_primary_cta')->label('Botón principal')->maxLength(40),
                            Forms\Components\TextInput::make('hero_secondary_cta')->label('Botón secundario')->maxLength(40),
                            Forms\Components\TextInput::make('about_title')->label('Título de «Nosotros»')->maxLength(160)->helperText('Puede resaltar con *asteriscos*.'),
                            Forms\Components\TextInput::make('cta_title')->label('Título del llamado a la acción final')->maxLength(160),
                        ]),
                        Forms\Components\Textarea::make('about_text')->label('Texto de «Nosotros»')->rows(3)->maxLength(400),
                        Forms\Components\Textarea::make('cta_text')->label('Texto del llamado a la acción final')->rows(2)->maxLength(300),
                        Forms\Components\Repeater::make('stats')->label('Cifras destacadas')
                            ->helperText('Use solo cifras reales y verificables. Se recomienda 4.')
                            ->schema([
                                Forms\Components\TextInput::make('value')->label('Cifra')->required()->maxLength(12),
                                Forms\Components\TextInput::make('label')->label('Descripción')->required()->maxLength(60),
                            ])->columns(2)->maxItems(4)->addActionLabel('Añadir cifra')->reorderable(),
                    ]),
                    Forms\Components\Tabs\Tab::make('SEO')->icon('heroicon-o-magnifying-glass')->schema([
                        Forms\Components\TextInput::make('seo_title')->label('Título de la página de inicio')->maxLength(70)->helperText('Aparece en la pestaña y en Google. Máx. 60 a 70 caracteres.'),
                        Forms\Components\Textarea::make('seo_description')->label('Descripción para buscadores')->rows(3)->maxLength(300)->helperText('Ideal: 120 a 160 caracteres.'),
                    ]),
                    Forms\Components\Tabs\Tab::make('Calculadora laboral')->icon('heroicon-o-calculator')->schema([
                        Forms\Components\Placeholder::make('wage_help')->hiddenLabel()->content('Salario mínimo diario vigente que usa la calculadora para topar la prima de antigüedad. Actualícelo cada año (CONASAMI).'),
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('calculator_min_wage')->label('Salario mínimo general (diario)')->numeric()->prefix('$')->minValue(1)->placeholder((string) config('despacho.calculator.minimum_wage')),
                            Forms\Components\TextInput::make('calculator_min_wage_border')->label('Salario mínimo zona libre de la frontera norte (diario)')->numeric()->prefix('$')->minValue(1)->placeholder((string) config('despacho.calculator.minimum_wage_border')),
                        ]),
                    ]),
                ])->persistTabInQueryString(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach (self::FIELDS as $key => [$type, $group]) {
            $value = $data[$key] ?? null;
            if (is_string($value)) {
                $value = trim($value);
            }
            Setting::set($key, $value === '' || $value === [] ? null : $value, $type, $group);
        }

        Notification::make()->title('Configuración guardada')->success()->send();
    }
}
