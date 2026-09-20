<?php

namespace Database\Seeders;

use App\Models\Attorney;
use Illuminate\Database\Seeder;

class AttorneySeeder extends Seeder
{
    public function run(): void
    {
        foreach (SiteData::get('attorneys') as $index => $attorney) {
            Attorney::firstOrCreate(['slug' => $attorney['slug']], [
                'name' => $attorney['name'],
                'position' => $attorney['position'],
                'bio_short' => $attorney['bioShort'],
                'bio' => $attorney['bio'],
                'education' => $attorney['education'],
                'credentials' => $attorney['credentials'],
                'experience' => $attorney['experience'],
                'memberships' => $attorney['memberships'],
                'languages' => $attorney['languages'],
                'areas' => $attorney['areas'],
                'linkedin' => null,
                'is_demo' => true,
                'is_active' => true,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
