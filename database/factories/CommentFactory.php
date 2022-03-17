<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
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
            'post_id' => $this->faker->numberBetween($min = 1, $max = 10),
            'message' => $this->faker->realText($maxNbChars = 50),
        ];
    }
}
