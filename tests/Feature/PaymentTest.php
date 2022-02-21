<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_saving_payment_in_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $payment = [
            'type' => Str::random(10),
            'details' => json_encode([
                'first_name' => Str::random(10),
                'last_name' =>  Str::random(10),
                'address' =>  Str::random(10),
            ]),
        ];

        $response = $this->post('/api/v1/payment/create', $payment);

        $response->assertStatus(201);
    }

    public function test_remove_payment_from_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $payment = Payment::factory()->create();

        $response = $this->delete('/api/v1/payment/'.$payment->uuid);

        $response->assertStatus(200);
    }

    public function test_update_payment_in_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $payment = Payment::factory()->create();

        $form = [
            'type' => Str::random(10),
            'details' => json_encode([
                'first_name' => Str::random(10),
                'last_name' =>  Str::random(10),
                'address' =>  Str::random(10),
            ]),
        ];

        $response = $this->put('/api/v1/payment/'.$payment->uuid,$form);

        $response->assertStatus(200);
    }

    public function test_show_all_payments()
    {
        $this->withoutExceptionHandling();

        Payment::factory()->count(10)->create();

        $response = $this->get('/api/v1/payments');

        $response->assertStatus(200);
    }

    public function test_show_specific_payment()
    {
        $this->withoutExceptionHandling();

        $payment = Payment::factory()->create();

        $response = $this->get('/api/v1/payment/'.$payment->uuid);

        $response->assertStatus(200);
    }
}
