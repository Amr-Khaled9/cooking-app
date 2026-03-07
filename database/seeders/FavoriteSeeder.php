<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
    }
}
