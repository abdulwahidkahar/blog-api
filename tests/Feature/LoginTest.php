<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_login_success()
    {
        User::factory()->create([
            'name' => 'Wahid',
            'email' => 'wahid@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $payload = [
            'email' => 'wahid@gmail.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/v1/auth/login', $payload);

        $response->assertStatus(200);

        $response->assertJsonStructure([
        'success',
        'message',
        'token',
        'user' => [
            'id',
            'name',
            'email',
        ],
        ])->assertJson([
            'success' => true,
            'message' => 'Login successfully',
        ]);
    }
}
