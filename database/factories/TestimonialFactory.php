<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quote' => fake()->sentence(14),
            'author' => fake()->name(),
            'area' => 'Derecho Corporativo',
            'is_demo' => false,
            'is_active' => true,
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }
}
