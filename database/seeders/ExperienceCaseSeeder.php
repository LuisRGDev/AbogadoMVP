<?php

namespace Database\Seeders;

use App\Models\ExperienceCase;
use Illuminate\Database\Seeder;

class ExperienceCaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SiteData::get('cases') as $index => $case) {
            ExperienceCase::firstOrCreate(['matter' => $case['matter'], 'area' => $case['area']], [
                'challenge' => $case['challenge'],
                'strategy' => $case['strategy'],
                'result' => $case['result'],
                'is_demo' => true,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
