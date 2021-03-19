<?php

namespace Tests\Feature;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    // THE FACTORIES AUTOMATICALLY "ADD" THE OBJECTS TO THE DATABASE
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_create_category(){
        $name = "Sports equipment";
        $response = $this->postJson("/api/categories", ["name" => $name]);

        $response
            ->assertStatus(201)
            ->assertJsonPath("category.name", $name);
    }

    public function test_can_update_category(){
        $category = Category::factory()->create();
        
        $response = $this->patchJson("/api/categories/{$category->id}", ["name" => "Clothes"]);

        $response
            ->assertStatus(200)
            ->assertJsonPath("category.name", "Clothes");
    }

    public function test_can_show_category(){
        Category::factory()->create();
        $response = $this->getJson("/api/categories/");

        $response->assertStatus(200);
    }

    public function test_can_delete_category(){

        $category = Category::factory()->create();
        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(204);
    }

    public function test_get_call_category_products(){

        $category = Category::factory()->create();
        Product::factory()->create(["category_id" => $category->id]);
        Product::factory()->create(["category_id" => $category->id]);
        $response = $this->getJson("/api/categories/products/?id={$category->id}");

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has("products", 2));
    }
}
