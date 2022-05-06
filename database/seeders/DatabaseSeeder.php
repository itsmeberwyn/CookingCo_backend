<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Reported_post::factory(10)->create();
        // \App\Models\Reported_user::factory(10)->create();
        // \App\Models\Reported_comment::factory(10)->create();
        // \App\Models\Feedback::factory(10)->create();
        // \App\Models\User::factory(10)->create();
        // \App\Models\Post::factory(10)->create();
        // \App\Models\Comment::factory(10)->create();


    }
}
