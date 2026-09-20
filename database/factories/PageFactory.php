<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'eyebrow' => 'Legal',
            'text' => null,
            'content' => '<h2>'.fake()->sentence(2).'</h2><p>'.fake()->paragraph().'</p>',
            'seo_title' => null,
            'seo_description' => null,
            'is_active' => true,
        ];
    }
}
