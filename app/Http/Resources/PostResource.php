<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author_id' => $this->user_id,
            'title' => $this->title,
            'body' => $this->body,
            'cover_image' => $this->cover_image,
            'is_published' => (bool) $this->is_published,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'List Posted successfully',
            'data' => $this->toArray($request),
        ]);
    }
}
