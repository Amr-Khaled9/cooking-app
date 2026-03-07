<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rating' => $this->faker->numberBetween(1, 5),
            'user_id' => \App\Models\User::inRandomOrder()->first()?->id ?? \App\Models\User::factory(),
            'recipe_id' => \App\Models\Recipe::inRandomOrder()->first()?->id ?? \App\Models\Recipe::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
