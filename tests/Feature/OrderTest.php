<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_saving_order_in_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $orderStatus = OrderStatus::factory()->create();
        $payment = Payment::factory()->create();
        $product = Product::factory()->create();
        $this->actingAs($user);

        $order = [
            'user_uuid' => $user->uuid,
            'order_status_uuid' => $orderStatus->uuid,
            'payment_uuid' => $payment->uuid,
            'products' => json_encode([
                'product' => $product->uuid,
                'quantity' =>  10,
            ]),
            'address' => json_encode([
                'billing' => Str::random(10),
                'shipping' =>  Str::random(10),
            ]),
        ];

        $response = $this->post('/api/v1/order/create', $order);

        $response->assertStatus(201);
    }

    public function test_remove_order_from_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $order = Order::factory()->create();

        $response = $this->delete('/api/v1/order/'.$order->uuid);

        $response->assertStatus(200);
    }

    public function test_update_order_in_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $orderStatus = OrderStatus::factory()->create();
        $payment = Payment::factory()->create();
        $product = Product::factory()->create();
        $this->actingAs($user);

        $order = Order::factory()->create();

        $form = [
            'user_uuid' => $user->uuid,
            'order_status_uuid' => $orderStatus->uuid,
            'payment_uuid' => $payment->uuid,
            'products' => json_encode([
                'product' => $product->uuid,
                'quantity' =>  10,
            ]),
            'address' => json_encode([
                'billing' => Str::random(10),
                'shipping' =>  Str::random(10),
            ]),
        ];

        $response = $this->put('/api/v1/order/'.$order->uuid,$form);

        $response->assertStatus(200);
    }

    public function test_show_specific_order()
    {
        $this->withoutExceptionHandling();

        $order = Order::factory()->create();

        $response = $this->get('/api/v1/order/'.$order->uuid);

        $response->assertStatus(200);
    }

    public function test_show_all_orders()
    {
        $this->withoutExceptionHandling();

        Order::factory()->count(10)->create();

        $response = $this->get('/api/v1/orders');

        $response->assertStatus(200);
    }

    public function test_shipment_locator()
    {
        $this->withoutExceptionHandling();

        Order::factory()->count(10)->create();

        $response = $this->get('/api/v1/orders/shipment-locator');

        $response->assertStatus(200);
    }

    public function test_dashboard()
    {
        $this->withoutExceptionHandling();

        Order::factory()->count(10)->create();

        $response = $this->get('/api/v1/orders/dashboard');

        $response->assertStatus(200);
    }


}
