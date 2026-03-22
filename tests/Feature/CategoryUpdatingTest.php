<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryUpdatingTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // ✅ success case
    public function test_category_found()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)
            ->put("/api/categories/{$category->id}", [
                'name' => 'Updated Category'
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category'
        ]);
    }

    // ✅ not found
    public function test_category_not_found()
    {
        $response = $this->actingAs($this->user)
            ->put("/api/categories/99999", [
                'name' => 'Updated Category'
            ]);

        $response->assertStatus(404);
    }

    // ✅ validation: required
    public function test_validation_required_name()
    {
        $category = Category::factory()->create([]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/categories/{$category->id}", []);

        $response->assertStatus(422);
    }

    // ✅ validation: must be string
    public function test_validation_name_must_be_string()
    {
        $category = Category::factory()->create([]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/categories/{$category->id}", [
                'name' => 123456
            ]);

        $response->assertStatus(422);
    }

    // ✅ validation: max 255
    public function test_validation_name_max_255()
    {
        $category = Category::factory()->create([]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/categories/{$category->id}", [
                'name' => str_repeat('a', 500)
            ]);

        $response->assertStatus(422);
    }
}
