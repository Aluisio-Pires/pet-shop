<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(PromotionSeeder::class);
        $this->call(FileSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(PaymentSeeder::class);
        $this->call(OrderStatusSeeder::class);
        $this->call(OrderSeeder::class);

    }
}
