<?php

namespace Database\Factories;

use App\Models\ExperienceCase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExperienceCase>
 */
class ExperienceCaseFactory extends Factory
{
    protected $model = ExperienceCase::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'matter' => fake()->sentence(3),
            'area' => 'Derecho Corporativo',
            'challenge' => fake()->sentence(12),
            'strategy' => fake()->sentence(12),
            'result' => fake()->sentence(12),
            'is_demo' => false,
            'is_active' => true,
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }
}
