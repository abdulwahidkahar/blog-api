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

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    public function index(): AnonymousResourceCollection
    {
        $posts = $this->postService->getAll();
        return PostResource::collection($posts);
    }

    public function store(StorePostRequest $request): StorePostResource
    {
        $validator = $request->validated();
        $userId = auth()->id(); // Get authenticated user ID
        $post = $this->postService->create($validator, $userId);
        return new StorePostResource($post);
    }

    public function show(Post $post): PostResource
    {
        $post = $this->postService->getById($post->id);
        return new PostResource($post);
    }

    public function update(StorePostRequest $request, Post $post): PostResource
    {
        $validator = $request->validated();
        $post = $this->postService->update($validator, $post);
        return new PostResource($post);
    }

    public function destroy(Post $post)
    {
        $this->postService->delete($post);
        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ]);
    }
}
