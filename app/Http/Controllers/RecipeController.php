<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecipeStoreRequest;
use App\Http\Requests\RecipeUpdateRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $query = Recipe::query()
            ->with(['user:id,name', 'category:id,name'])
            ->withCount(['ratings', 'favoritedBy']);

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->has('rating')) {
            $query->orderByDesc('ratings_count');
        }
        if($request->has('search')){
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        $recipes = $query->paginate(20);

        return RecipeResource::collection($recipes);
    }

    public function store(RecipeStoreRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('recipes', 'public');
            $data['image'] = $imagePath;
        }

        $recipe = Recipe::create($data);

        if (isset($data['image'])) {
            $recipe->image_url = asset('storage/' . $data['image']);
        }

        return $this->success($recipe, 'Recipe created successfully', 201);
    }


    public function show(string $id)
    {
        $recipe = Recipe::find($id);

        if (!$recipe) {
            return $this->error('Recipe not found', 404);
        }

        return $this->success($recipe, 'Recipe retrieved successfully');
    }

    public function update(RecipeUpdateRequest $request, string $id)
    {
        $recipe = Recipe::find($id);

        if (!$recipe) {
            return $this->error('Recipe not found', 404);
        }

        // Authorization
        if ($recipe->user_id !== auth()->id()) {
            return $this->error('Not allowed to update this recipe', 403);
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('recipes', 'public');
            $data['image'] = $imagePath;
        }

        $recipe->update($data);

        return $this->success($recipe, 'Recipe updated successfully');
    }


    public function destroy(string $id)
    {
        $recipe = Recipe::find($id);

        if (!$recipe) {
            return $this->error('Recipe not found', 404);
        }

        // Authorization
        if ($recipe->user_id !== auth()->id()) {
            return $this->error('Not allowed to update this recipe', 403);
        }

        $recipe->delete();

        return $this->success(null, 'Recipe deleted successfully');
    }
}
