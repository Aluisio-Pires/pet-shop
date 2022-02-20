<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_saving_category_in_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $category = [
            'title' => Str::random(10),
            'slug' => Str::random(10),
        ];

        $response = $this->post('/api/v1/category/create', $category);

        $response->assertStatus(201);
    }

    public function test_remove_category_from_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $category = Category::factory()->create();

        $response = $this->delete('/api/v1/category/'.$category->uuid);

        $response->assertStatus(200);
    }

    public function test_update_category_in_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $category = Category::factory()->create();

        $form = [
            'title' => 'test',
            'slug' => 'test',
        ];

        $response = $this->put('/api/v1/category/'.$category->uuid,$form);

        $response->assertStatus(200);
    }

    public function test_show_specific_category()
    {
        $this->withoutExceptionHandling();

        $category = Category::factory()->create();

        $response = $this->get('/api/v1/category/'.$category->uuid);

        $response->assertStatus(200);
    }

    public function test_show_all_categories()
    {
        $this->withoutExceptionHandling();

        Category::factory()->count(10)->create();

        $response = $this->get('/api/v1/categories');

        $response->assertStatus(200);
    }
}
