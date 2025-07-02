<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        \App\Models\User::factory(5)->create()->each(function ($user) {
            \App\Models\UserProfile::create([
                'user_id' => $user->id,
                'mood' => fake()->randomElement(['happy','sad','neutral','excited','calm']),
                'personality' => json_encode([
                    'openness' => fake()->randomFloat(2, 0, 1),
                    'conscientiousness' => fake()->randomFloat(2, 0, 1),
                    'extraversion' => fake()->randomFloat(2, 0, 1),
                    'agreeableness' => fake()->randomFloat(2, 0, 1),
                    'neuroticism' => fake()->randomFloat(2, 0, 1),
                ]),
            ]);
        });
    }
}