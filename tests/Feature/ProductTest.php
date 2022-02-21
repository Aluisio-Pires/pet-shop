<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_saving_product_in_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);
        $category = Category::factory()->create();

        $product = [
            'category_uuid' => $category->uuid,
            'title' => Str::random(10),
            'price' => 50.5,
            'description' => Str::random(100),
            'metadata' => json_encode([
                'brand' => 'test',
                'image' => 'test',
            ]),
        ];

        $response = $this->post('/api/v1/product/create', $product);

        $response->assertStatus(201);
    }

    public function test_remove_product_from_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $response = $this->delete('/api/v1/product/'.$product->uuid);

        $response->assertStatus(200);
    }

    public function test_update_product_in_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $category = Category::factory()->create();

        $form = [
            'category_uuid' => $category->uuid,
            'title' => Str::random(10),
            'price' => 50.5,
            'description' => Str::random(100),
            'metadata' => json_encode([
                'brand' => 'test',
                'image' => 'test',
            ]),
        ];

        $response = $this->put('/api/v1/product/'.$product->uuid,$form);

        $response->assertStatus(200);
    }

    public function test_show_specific_product()
    {
        $this->withoutExceptionHandling();

        $product = Product::factory()->create();

        $response = $this->get('/api/v1/product/'.$product->uuid);

        $response->assertStatus(200);
    }

    public function test_show_all_products()
    {
        $this->withoutExceptionHandling();

        Product::factory()->count(10)->create();

        $response = $this->get('/api/v1/products');

        $response->assertStatus(200);
    }
}
