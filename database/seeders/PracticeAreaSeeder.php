<?php

namespace Database\Seeders;

use App\Models\PracticeArea;
use Illuminate\Database\Seeder;

class PracticeAreaSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SiteData::get('areas') as $index => $area) {
            PracticeArea::firstOrCreate(['slug' => $area['slug']], [
                'num' => $area['num'],
                'icon' => $area['icon'],
                'title' => $area['title'],
                'short' => $area['short'],
                'overview' => $area['overview'],
                'matters' => $area['matters'],
                'needs' => $area['needs'],
                'process' => collect($area['process'])->map(fn (array $step): array => ['title' => $step[0], 'description' => $step[1]])->all(),
                'faqs' => collect($area['faqs'])->map(fn (array $faq): array => ['question' => $faq[0], 'answer' => $faq[1]])->all(),
                'seo_title' => $area['seoTitle'],
                'seo_description' => $area['seoDescription'],
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
