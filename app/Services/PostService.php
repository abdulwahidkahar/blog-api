<?php

namespace App\Services;

use App\Models\Post;

class PostService
{
    public function getAll(int $perPage = 10)
    {
        return Post::latest()->paginate($perPage);
    }

    public function create(array $data, int $userId = null)
    {
        if (isset($data['cover_image'])) {
            $data['cover_image'] = $data['cover_image']->store('posts', 'public');
        }
        
        // Add user_id if not provided
        if ($userId) {
            $data['user_id'] = $userId;
        }
        
        // Generate slug from title if not provided
        if (!isset($data['slug'])) {
            $data['slug'] = \Str::slug($data['title']);
        }
        
        return Post::create($data);
    }

    public function getById(int $id)
    {
        return Post::findOrFail($id);
    }

    public function update(array $data, Post $post)
    {
        if (isset($data['cover_image'])) {
            $data['cover_image'] = $data['cover_image']->store('posts', 'public');
        }

        $post->update($data);
        return $post;
    }

    public function delete(Post $post)
    {
        $post->delete();
        return $post;
    }

    public function restore(int $id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();
        return $post;
    }
}
