<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'excerpt' => fake()->sentence(16),
            'body' => '<h2>'.fake()->sentence(3).'</h2><p>'.fake()->paragraph().'</p><h2>'.fake()->sentence(3).'</h2><p>'.fake()->paragraph().'</p>',
            'category' => fake()->randomElement(['Corporativo', 'Contratos', 'Empresas']),
            'date' => fake()->dateTimeBetween('-6 months', '-1 day'),
            'read_time' => null,
            'seo_title' => null,
            'seo_description' => null,
            'is_published' => true,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => ['is_published' => false]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (): array => ['date' => now()->addWeek()]);
    }
}
