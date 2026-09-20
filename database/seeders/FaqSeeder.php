<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SiteData::get('faqs') as $index => [$question, $answer]) {
            Faq::firstOrCreate(['question' => $question], [
                'answer' => $answer,
                'group' => 'general',
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
