<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\StorePostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Posts
 * 
 * API endpoints for managing blog posts. All endpoints require JWT authentication.
 */
class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    /**
     * Get all posts
     * 
     * Retrieve a paginated list of all blog posts.
     * 
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $posts = $this->postService->getAll();
        return PostResource::collection($posts);
    }

    /**
     * Create a new post
     * 
     * Create a new blog post with optional cover image upload.
     * 
     * @param StorePostRequest $request
     * @return StorePostResource
     */
    public function store(StorePostRequest $request): StorePostResource
    {
        $validator = $request->validated();
        $userId = auth()->id(); // Get authenticated user ID
        $post = $this->postService->create($validator, $userId);
        return new StorePostResource($post);
    }

    /**
     * Get a specific post
     * 
     * Retrieve a single blog post by its ID.
     * 
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post): PostResource
    {
        $post = $this->postService->getById($post->id);
        return new PostResource($post);
    }

    /**
     * Update a post
     * 
     * Update an existing blog post with new data.
     * 
     * @param StorePostRequest $request
     * @param Post $post
     * @return PostResource
     */
    public function update(StorePostRequest $request, Post $post): PostResource
    {
        $validator = $request->validated();
        $post = $this->postService->update($validator, $post);
        return new PostResource($post);
    }

    /**
     * Delete a post
     * 
     * Soft delete a blog post (can be restored later).
     * 
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Post $post)
    {
        $this->postService->delete($post);
        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ]);
    }
}
