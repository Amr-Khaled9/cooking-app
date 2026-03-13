<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function rate(Request $request, $recipeId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $recipe = Recipe::findOrFail($recipeId);
        $user = $request->user();

        
        $existing = Rating::where('user_id', $user->id)
            ->where('recipe_id', $recipe->id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'You have already rated this recipe.'
            ], 400);
        }

        $rating = Rating::create([
            'user_id' => $user->id,
            'recipe_id' => $recipe->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'message' => 'Recipe rated successfully',
            'rating' => $rating
        ]);
    }
}
