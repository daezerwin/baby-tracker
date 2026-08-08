<?php

namespace Database\Factories;

use App\Models\Baby;
use App\Models\BabyStory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BabyStory>
 */
class BabyStoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'baby_id' => Baby::factory(),
            'caption' => $this->faker->sentence(),
            'media_path' => null,
            'media_type' => null,
            'occurred_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
