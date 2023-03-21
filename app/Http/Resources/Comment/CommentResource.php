<?php

namespace App\Http\Resources\Comment;

use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $id
 * @property string $post_id
 * @property string $user_id
 * @property string $content
 * @property \DateTime|null $created_at
 * @property \DateTime|null $updated_at
 * @property \DateTime|null $deleted_at
 * @property \App\Models\Post $post
 * @property \App\Models\User $user
 */
class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'content'   => $this->content,
            'createdAt' => $this->created_at->format('c'),
            'updatedAt' => $this->updated_at->format('c'),
            'post'      => new PostResource($this->whenLoaded('post')),
            'user'      => new UserResource($this->whenLoaded('user')),
        ];
    }
}
