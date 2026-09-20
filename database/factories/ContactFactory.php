<?php

namespace Database\Factories;

use App\Enums\ContactStatus;
use App\Enums\ContactType;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    protected $model = Contact::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => ContactType::Contact,
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->numerify('55 #### ####'),
            'area' => 'Derecho Corporativo',
            'message' => fake()->paragraph(),
            'status' => ContactStatus::Pending,
        ];
    }

    public function appointment(): static
    {
        return $this->state(fn (): array => [
            'type' => ContactType::Appointment,
            'preferred_date' => now()->addDays(5)->toDateString(),
            'preferred_slot' => 'manana',
            'meeting_mode' => 'videollamada',
        ]);
    }
}
