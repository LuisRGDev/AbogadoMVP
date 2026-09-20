<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Faq>
 */
class FaqFactory extends Factory
{
    protected $model = Faq::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question' => '¿'.fake()->unique()->sentence(5).'?',
            'answer' => fake()->sentence(16),
            'group' => 'general',
            'is_active' => true,
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }
}
