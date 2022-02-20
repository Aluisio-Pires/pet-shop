<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_show_all_posts()
    {
        $this->withoutExceptionHandling();

        Post::factory()->count(10)->create();

        $response = $this->get('/api/v1/main/blog');

        $response->assertStatus(200);
    }
    public function test_show_specific_post()
    {
        $this->withoutExceptionHandling();

        $post = Post::factory()->create();

        $response = $this->get('/api/v1/main/blog/'.$post->uuid);

        $response->assertStatus(200);
    }
}
