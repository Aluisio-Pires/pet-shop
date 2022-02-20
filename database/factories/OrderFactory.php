<?php

namespace Database\Factories;

use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_uuid' => User::factory(),
            'order_status_uuid' => OrderStatus::factory(),
            'payment_uuid' => Payment::factory(),
            'products' => json_encode([
                'product' => Product::factory(),
                'quantity' =>  $this->faker->numberBetween(0,30),
            ]),
            'address' => json_encode([
                'billing' => Str::random(10),
                'shipping' =>  Str::random(10),
            ]),
            'delivery_fee' => 15,
            'amount' => $this->faker->numberBetween(0,300),
        ];
    }
}
