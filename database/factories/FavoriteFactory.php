<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Favorite>
 */
class FavoriteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            $users = User::all();
    $recipes = Recipe::all();

    foreach ($users as $user) {
        $randomRecipes = $recipes->random(3);

        foreach ($randomRecipes as $recipe) {
            Favorite::create([
                'user_id' => $user->id,
                'recipe_id' => $recipe->id,
            ]);
        }
    }
        ];
    }
}
