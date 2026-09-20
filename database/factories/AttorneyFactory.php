<?php

namespace Database\Factories;

use App\Models\Attorney;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Attorney>
 */
class AttorneyFactory extends Factory
{
    protected $model = Attorney::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'name' => $name,
            'position' => fake()->randomElement(['Socio(a) Director(a)', 'Socio(a)', 'Asociado(a) Senior']),
            'bio_short' => fake()->sentence(14),
            'bio' => [fake()->paragraph(), fake()->paragraph()],
            'education' => ['Licenciatura en Derecho — '.fake()->company()],
            'credentials' => 'Cédula profesional '.fake()->numerify('########'),
            'experience' => [fake()->jobTitle().' — '.fake()->company()],
            'memberships' => [fake()->company()],
            'languages' => ['Español', 'Inglés'],
            'areas' => ['Derecho Corporativo'],
            'linkedin' => null,
            'is_demo' => false,
            'is_active' => true,
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }
}
