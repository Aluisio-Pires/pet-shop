<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class FileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_show_specific_file()
    {
        $this->withoutExceptionHandling();

        $file = File::factory()->create();

        $response = $this->get('/api/v1/file/'.$file->uuid);

        $response->assertStatus(200);
    }

    public function test_saving_file_in_database()
    {
        $this->withoutExceptionHandling();
        $user= User::factory()->create();
        $this->actingAs($user);

        $file = [
            'name' => Str::random(10),
            'image' => UploadedFile::fake()->image('test.png'),
        ];

        /*to test this feature you must enable gd extension in your php.ini file
         * just open your php file and edit your php.ini as text
         * hit ctrl+f and search for "gd"
         * remove the ";" before "extension=gd"
         * save and run this test
         */

        $response = $this->post('/api/v1/file/upload', $file);

        $response->assertStatus(201);
    }
}
