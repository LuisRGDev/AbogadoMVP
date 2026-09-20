<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'general' => [
                'firm_name' => ['text', '[NOMBRE DEL DESPACHO]'],
                'firm_tagline' => ['text', 'Estrategia jurídica para decisiones que importan.'],
                'firm_description' => ['textarea', 'Despacho jurídico en Ciudad de México que brinda asesoría estratégica a personas, empresas y organizaciones con claridad, criterio y rigor.'],
            ],
            'contact' => [
                'phone' => ['text', '+52 55 0000 0000'],
                'email' => ['text', 'contacto@despacho.example'],
                'whatsapp_number' => ['text', '+52 55 0000 0000'],
                'whatsapp_message' => ['textarea', 'Hola, me gustaría solicitar información para una consulta jurídica.'],
                'address_line1' => ['text', '[Calle y número]'],
                'address_line2' => ['text', '[Colonia], [Alcaldía]'],
                'city' => ['text', 'Ciudad de México'],
                'state' => ['text', 'CDMX'],
                'postal_code' => ['text', '00000'],
                'country' => ['text', 'México'],
                'maps_query' => ['text', 'Ciudad de México, México'],
                'hours' => ['text', 'Lunes a viernes · 9:00 a 18:00'],
            ],
            'seo' => [
                'seo_title' => ['text', 'Despacho jurídico en Ciudad de México'],
                'seo_description' => ['textarea', 'Asesoría jurídica estratégica en Ciudad de México para personas, empresas y organizaciones: derecho corporativo, litigio, laboral, inmobiliario, civil y mercantil, y propiedad intelectual.'],
            ],
        ];

        foreach ($settings as $group => $items) {
            foreach ($items as $key => [$type, $value]) {
                Setting::firstOrCreate(['key' => $key], ['value' => $value, 'type' => $type, 'group' => $group]);
            }
        }

        SettingService::flush();
    }
}
