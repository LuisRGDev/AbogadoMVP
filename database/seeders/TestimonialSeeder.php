<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SiteData::get('testimonials') as $index => $testimonial) {
            Testimonial::firstOrCreate(['quote' => $testimonial['quote'], 'author' => $testimonial['author']], [
                'area' => $testimonial['area'],
                'is_demo' => true,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
