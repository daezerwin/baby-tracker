<?php

namespace Database\Factories;

use App\Models\Baby;
use App\Models\BabyPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BabyPhoto>
 */
class BabyPhotoFactory extends Factory
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
            'path' => 'baby-photos/'.$this->faker->uuid().'.jpg',
            'caption' => null,
            'taken_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'is_profile' => false,
        ];
    }
}
