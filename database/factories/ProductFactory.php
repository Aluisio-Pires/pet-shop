<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_uuid' => Category::factory(),
            'title' => Str::random(10),
            'price' => $this->faker->numberBetween(0,300),
            'description' => Str::random(100),
            'metadata' => json_encode([
                'brand' => Brand::factory(),
                'image' =>  File::factory(),
            ]),
        ];
    }
}
