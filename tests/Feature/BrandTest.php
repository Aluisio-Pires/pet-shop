<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class BrandTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_saving_brand_in_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $brand = [
            'title' => Str::random(10),
            'slug' => Str::random(10),
        ];

        $response = $this->post('/api/v1/brand/create', $brand);

        $response->assertStatus(201);
    }

    public function test_remove_brand_from_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $brand = Brand::factory()->create();

        $response = $this->delete('/api/v1/brand/'.$brand->uuid);

        $response->assertStatus(200);
    }

    public function test_update_brand_in_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $brand = Brand::factory()->create();

        $form = [
            'title' => 'test',
            'slug' => 'test',
        ];

        $response = $this->put('/api/v1/brand/'.$brand->uuid,$form);

        $response->assertStatus(200);
    }

    public function test_show_specific_brand()
    {
        $this->withoutExceptionHandling();

        $brand = Brand::factory()->create();

        $response = $this->get('/api/v1/brand/'.$brand->uuid);

        $response->assertStatus(200);
    }

    public function test_show_all_brands()
    {
        $this->withoutExceptionHandling();

        Brand::factory()->count(10)->create();

        $response = $this->get('/api/v1/brands');

        $response->assertStatus(200);
    }


}
