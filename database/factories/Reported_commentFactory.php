<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class Reported_commentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(1, 30),
            'comment_id' => rand(1, 30),
            'reason' => $this->faker->realText($maxNbChars = 50),
        ];
    }
}
