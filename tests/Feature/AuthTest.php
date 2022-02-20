<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_login()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user);
        $form = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->post('/api/v1/admin/login', $form);

        $response->assertStatus(200);
    }

    public function test_admin_logout()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user);
        $form = [
            'email' => $user->email,
            'password' => 'password',
        ];
        $this->post('/api/v1/admin/login', $form);
        $response = $this->get('/api/v1/admin/logout');

        $response->assertStatus(200);
    }

    public function test_user_login()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user);
        $form = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->post('/api/v1/user/login', $form);

        $response->assertStatus(200);
    }

    public function test_user_logout()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user);
        $form = [
            'email' => $user->email,
            'password' => 'password',
        ];
        $this->post('/api/v1/user/login', $form);
        $response = $this->get('/api/v1/user/logout');

        $response->assertStatus(200);
    }

    public function test_change_password()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user);
        $formForgot = [
            'email' => $user->email,
        ];
        $response = $this->post('/api/v1/user/forgot-password', $formForgot);
        //dd(json_decode($response->getContent())->jwt_token);
        $response->assertStatus(200);

        $formReset = [
            'email' => $user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'token' => $response->json()['jwt_token'],
        ];

        $response = $this->put('/api/v1/user/reset-password-token', $formReset);

        $response->assertStatus(200);
    }


}
