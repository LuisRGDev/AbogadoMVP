<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::firstOrCreate(['slug' => 'nosotros'], [
            'title' => 'Un despacho construido sobre criterio y confianza.',
            'eyebrow' => 'Nosotros',
            'text' => 'Conozca nuestra forma de trabajo, nuestros principios y lo que puede esperar al colaborar con nosotros.',
            'seo_title' => 'Nosotros',
            'seo_description' => 'Conozca los principios, la forma de trabajo y el enfoque de nuestro despacho jurídico en Ciudad de México.',
            'is_active' => true,
        ]);

        foreach (SiteData::get('legal.pages') as $slug => $page) {
            Page::firstOrCreate(['slug' => $slug], [
                'title' => $page['title'],
                'eyebrow' => 'Legal',
                'content' => SiteData::sectionsToHtml($page['sections']),
                'seo_title' => $page['title'],
                'seo_description' => $page['title'].' de este sitio.',
                'is_active' => true,
            ]);
        }
    }
}
