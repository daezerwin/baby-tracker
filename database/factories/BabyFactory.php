<?php

namespace Database\Factories;

use App\Models\Baby;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Baby>
 */
class BabyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->firstName(),
            'sex' => $this->faker->randomElement(['male', 'female', 'other']),
            'date_of_birth' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
