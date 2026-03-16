<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Recipe;
use App\Traits\ApiResponse;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $myFavorite = Favorite::where('user_id', auth()->id())->get();

        return $this->success($myFavorite, "Successfully Fetched My Favorite", 200);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'recipe_id' => 'required|exists:recipes,id'
        ]);

        $exists = Favorite::where('user_id', auth()->id())
            ->where('recipe_id', $request->recipe_id)
            ->exists();

        if ($exists) {
            return $this->error('Recipe already in favorites', 409);
        }

        $myFavorite = Favorite::create([
            'user_id' => auth()->id(),
            'recipe_id' => $request->recipe_id
        ]);

        return $this->success($myFavorite, "Successfully Created My Favorite", 201);
    }

    public function delete($id)
    {
        $deleted = Favorite::where('recipe_id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        if (!$deleted) {
            return $this->error('Favorite not found', 404);
        }

        return $this->success(null, "Successfully Deleted My Favorite", 200);
    }
}
