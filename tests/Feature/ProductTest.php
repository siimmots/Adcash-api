<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    // THE FACTORIES AUTOMATICALLY "ADD" THE OBJECTS TO THE DATABASE
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_create_product(){
        $name = "Dumbbell";
        $category = Category::factory()->create();
        $response = $this->postJson("/api/products", ["name" => $name, "category_id" => $category->id]);

        $response
            ->assertStatus(201)
            ->assertJsonPath("product.name", $name)
            ->assertJsonPath("product.category_id", $category->id);
    }

    
    public function test_can_update_product(){
        $product = Product::factory()->create();
        
        $response = $this->patchJson("/api/products/{$product->id}", ["name" => "Clothes"]);

        $response
            ->assertStatus(200)
            ->assertJsonPath("product.name", "Clothes");
    }

    
    public function test_can_show_product(){
        Product::factory()->create();
        $response = $this->getJson("/api/products/");

        $response->assertStatus(200);
    }

    public function test_can_delete_category(){

        $product = Product::factory()->create();
        
        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);
    }
}
