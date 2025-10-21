<?php

namespace Tests\Feature;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_register()
    {
        $payload = [
            'name' => 'Wahid',
            'email' => 'wahid@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson('/api/v1/register', $payload);
        $response->assertStatus(201);

        $user = User::latest()->first();

        $resource = (new UserResource($user))->response()->getData(true);

        $response->assertJson([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => $resource['data']
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Wahid',
            'email' => 'wahid@gmail.com',
        ]);
    }
}
