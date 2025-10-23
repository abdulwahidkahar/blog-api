<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user and get JWT token
        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
    }

    protected function getAuthHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_user_can_create_post()
    {
        $postData = [
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
        ];

        $response = $this->postJson('/api/v1/posts', $postData, $this->getAuthHeaders());

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'author_id',
                        'title',
                        'body',
                        'cover_image',
                        'is_published',
                        'published_at',
                        'created_at',
                        'updated_at',
                    ]
                ]);

        $this->assertDatabaseHas('posts', [
            'title' => $postData['title'],
            'body' => $postData['body'],
            'user_id' => $this->user->id,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_user_can_create_post_with_cover_image()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('cover.jpg');

        $postData = [
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'cover_image' => $file,
        ];

        $response = $this->postJson('/api/v1/posts', $postData, $this->getAuthHeaders());

        $response->assertStatus(201);

        // Assert file was stored
        Storage::disk('public')->assertExists('posts/' . $file->hashName());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_user_can_view_all_posts()
    {
        // Create some posts
        Post::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/v1/posts', $this->getAuthHeaders());

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'author_id',
                            'title',
                            'body',
                            'cover_image',
                            'is_published',
                            'published_at',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_user_can_view_single_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/v1/posts/{$post->id}", $this->getAuthHeaders());

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'author_id',
                        'title',
                        'body',
                        'cover_image',
                        'is_published',
                        'published_at',
                        'created_at',
                        'updated_at',
                    ]
                ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_user_can_update_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'title' => 'Updated Title',
            'body' => 'Updated body content',
        ];

        $response = $this->putJson("/api/v1/posts/{$post->id}", $updateData, $this->getAuthHeaders());

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'author_id',
                        'title',
                        'body',
                        'cover_image',
                        'is_published',
                        'published_at',
                        'created_at',
                        'updated_at',
                    ]
                ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'body' => 'Updated body content',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_user_can_delete_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/v1/posts/{$post->id}", [], $this->getAuthHeaders());

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Post deleted successfully'
                ]);

        $this->assertSoftDeleted('posts', [
            'id' => $post->id,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function unauthenticated_user_cannot_access_posts()
    {
        $response = $this->getJson('/api/v1/posts');

        $response->assertStatus(401);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function unauthenticated_user_cannot_create_post()
    {
        $postData = [
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
        ];

        $response = $this->postJson('/api/v1/posts', $postData);

        $response->assertStatus(401);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function post_creation_requires_title()
    {
        $postData = [
            'body' => $this->faker->paragraph,
        ];

        $response = $this->postJson('/api/v1/posts', $postData, $this->getAuthHeaders());

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['title']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function post_creation_requires_body()
    {
        $postData = [
            'title' => $this->faker->sentence,
        ];

        $response = $this->postJson('/api/v1/posts', $postData, $this->getAuthHeaders());

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['body']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function post_creation_validates_cover_image_type()
    {
        $file = UploadedFile::fake()->create('document.pdf', 1000);

        $postData = [
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'cover_image' => $file,
        ];

        $response = $this->postJson('/api/v1/posts', $postData, $this->getAuthHeaders());

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['cover_image']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function post_creation_validates_cover_image_size()
    {
        $file = UploadedFile::fake()->image('cover.jpg')->size(3000); // 3MB

        $postData = [
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'cover_image' => $file,
        ];

        $response = $this->postJson('/api/v1/posts', $postData, $this->getAuthHeaders());

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['cover_image']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function post_slug_is_generated_automatically()
    {
        $postData = [
            'title' => 'My Awesome Post Title',
            'body' => $this->faker->paragraph,
        ];

        $response = $this->postJson('/api/v1/posts', $postData, $this->getAuthHeaders());

        $response->assertStatus(201);

        $this->assertDatabaseHas('posts', [
            'title' => 'My Awesome Post Title',
            'slug' => 'my-awesome-post-title',
        ]);
    }
}
