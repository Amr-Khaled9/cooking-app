<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'ingredients' => $this->faker->paragraph(),
            'steps' => $this->faker->paragraph(),
            'image' => $this->faker->imageUrl(640, 480, 'food'),

            'user_id' => User::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
