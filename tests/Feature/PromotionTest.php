<?php

namespace Tests\Feature;

use App\Models\Promotion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PromotionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_show_all_promotions()
    {
        $this->withoutExceptionHandling();

        Promotion::factory()->count(10)->create();

        $response = $this->get('/api/v1/main/promotions');

        $response->assertStatus(200);
    }
}
