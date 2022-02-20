<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => Str::random(10),
            'details' => json_encode([
                'first_name' => Str::random(10),
                'last_name' =>  Str::random(10),
                'address' =>  Str::random(10),
            ]),
        ];
    }
}
