<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketsType>
 */
class TicketsTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => $this->faker->randomElement([1,2,3]),
            'name' => $this->faker->randomElement(['VIP', 'Free', 'Middle']),
            'price' => 1000,
            'quantity' => 5,
            'real_quantity' => 10,
            'total_quantity' => 15,
            'description' => "short description",
        ];
    }
}
