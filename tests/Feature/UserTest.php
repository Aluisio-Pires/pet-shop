<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_saving_user_in_database()
    {
        $this->withoutExceptionHandling();
        $user = [
            'first_name' => Str::random(10),
            'last_name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'address' => Str::random(10),
            'phone_number' => Str::random(10),
            'is_marketing' => '0',
        ];

        $response = $this->post('/api/v1/user/create', $user);

        $response->assertStatus(201);
    }
    public function test_updating_user_in_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $update = [
            'first_name' => Str::random(10),
            'last_name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'address' => Str::random(10),
            'phone_number' => Str::random(10),
            'is_marketing' => '0',
        ];

        $response = $this->put('/api/v1/user/edit', $update);

        $response->assertStatus(200);
    }

    public function test_get_user_data_from_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/api/v1/user');

        $response->assertStatus(200);
    }

    public function test_delete_user_from_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $response = $this->delete('/api/v1/user');

        $response->assertStatus(200);
    }

    public function test_get_user_orders_from_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/api/v1/user/orders');

        $response->assertStatus(200);
    }
    public function test_saving_admin_in_database()
    {
        $this->withoutExceptionHandling();
        $user = [
            'first_name' => Str::random(10),
            'last_name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'address' => Str::random(10),
            'phone_number' => Str::random(10),
            'is_marketing' => '0',
        ];

        $response = $this->post('/api/v1/admin/create', $user);

        $response->assertStatus(201);
    }
    public function test_get_user_list_from_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/api/v1/admin/user-listing');

        $response->assertStatus(200);
    }

    public function test_admin_delete_user_from_database()
    {
        $this->withoutExceptionHandling();
        $admin = User::factory()->create();
        $user= User::factory()->create();
        $this->actingAs($admin);

        $response = $this->delete('/api/v1/admin/user-delete/'.$user->uuid);

        $response->assertStatus(200);
    }

}
