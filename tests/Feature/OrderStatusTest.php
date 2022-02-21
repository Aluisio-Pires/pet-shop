<?php

namespace Tests\Feature;

use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_saving_order_status_in_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $orderStatus = [
            'title' => Str::random(10),
        ];

        $response = $this->post('/api/v1/order-status/create', $orderStatus);

        $response->assertStatus(201);
    }

    public function test_remove_order_status_from_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $orderStatus = OrderStatus::factory()->create();

        $response = $this->delete('/api/v1/order-status/'.$orderStatus->uuid);

        $response->assertStatus(200);
    }

    public function test_update_order_status_in_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $orderStatus = OrderStatus::factory()->create();

        $form = [
            'title' => 'test',
        ];

        $response = $this->put('/api/v1/order-status/'.$orderStatus->uuid,$form);

        $response->assertStatus(200);
    }

    public function test_show_specific_order_status()
    {
        $this->withoutExceptionHandling();

        $orderStatus = OrderStatus::factory()->create();

        $response = $this->get('/api/v1/order-status/'.$orderStatus->uuid);

        $response->assertStatus(200);
    }

    public function test_show_all_order_statuses()
    {
        $this->withoutExceptionHandling();

        OrderStatus::factory()->count(10)->create();

        $response = $this->get('/api/v1/order-statuses');

        $response->assertStatus(200);
    }
}
