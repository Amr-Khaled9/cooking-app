<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'ingredients' => $this->ingredients,
            'steps' => $this->steps,
            'image' => $this->image,

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],

            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],

            'ratings_count' => $this->ratings_count,
            'favorites_count' => $this->favorited_by_count,

            'created_at' => $this->created_at,
        ];
    }
}
