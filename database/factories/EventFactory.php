<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => $this->faker->randomElement(['Autre', 'Concert-Spectacle', 'DinerGala', 'Festival', 'Formation']),
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph,
            'date' => $this->faker->dateTimeBetween('+1 week', '+1 year'),
            'image' => $this->faker->imageUrl(640, 480, 'events', true, 'Faker'),
            'city' => $this->faker->city,
            'address' => $this->faker->address,
            'status' => $this->faker->randomElement(['upcoming', 'completed', 'cancelled']),
            'create_on' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
