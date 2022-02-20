<?php

namespace Database\Factories;

use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promotion>
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => Str::random(10),
            'content' => Str::random(100),
            'metadata' => json_encode([
                'valid_from' => $this->faker->date,
                'valid_to' => $this->faker->date,
                'image' =>  File::factory(),
            ]),
        ];
    }
}
