<?php

namespace Database\Factories;

use App\Models\PracticeArea;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PracticeArea>
 */
class PracticeAreaFactory extends Factory
{
    protected $model = PracticeArea::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = 'Derecho '.fake()->unique()->words(2, true);

        return [
            'slug' => Str::slug($title),
            'num' => str_pad((string) fake()->numberBetween(1, 9), 2, '0', STR_PAD_LEFT),
            'icon' => fake()->randomElement(['building', 'columns', 'people', 'house', 'document', 'mark']),
            'title' => $title,
            'short' => fake()->sentence(12),
            'overview' => fake()->paragraph(),
            'matters' => [fake()->sentence(4), fake()->sentence(4)],
            'needs' => [fake()->sentence(6)],
            'process' => [['title' => 'Evaluación', 'description' => fake()->sentence()]],
            'faqs' => [['question' => '¿'.fake()->sentence(4).'?', 'answer' => fake()->sentence(10)]],
            'seo_title' => null,
            'seo_description' => null,
            'is_active' => true,
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }
}
