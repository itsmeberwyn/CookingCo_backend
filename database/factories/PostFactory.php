<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween($min = 1, $max = 10),
            'caption' => $this->faker->realText($maxNbChars = 50),
            'tag' => '["Breakfast", "Lunch", "Dinner"]',
            'post_image' => "1.png",
        ];
    }
}
