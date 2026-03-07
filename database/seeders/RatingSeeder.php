<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $recipes = Recipe::all();

        foreach (range(1, 100) as $i) {
            $user = $users->random();
            $recipe = $recipes->random();

            // تأكد من مفيش تقييم مكرر لنفس المستخدم ونفس الوصفة
            if (!Rating::where('user_id', $user->id)->where('recipe_id', $recipe->id)->exists()) {
                Rating::create([
                    'user_id' => $user->id,
                    'recipe_id' => $recipe->id,
                    'rating' => rand(1, 5),
                ]);
            }
        }
    }
}
