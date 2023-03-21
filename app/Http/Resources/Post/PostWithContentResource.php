<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;

class PostWithContentResource extends PostResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'slug'          => $this->slug,
            'title'         => $this->title,
            'content'       => $this->content,
            'publishedAt'   => $this->published_at?->format('c'),
            'createdAt'     => $this->created_at?->format('c'),
            'updatedAt'     => $this->updated_at?->format('c'),
            'category'      => new CategoryResource($this->whenLoaded('category')),
            'user'          => new UserResource($this->whenLoaded('user')),
            'comments'      => new CommentCollection($this->whenLoaded('comments')),
        ];
    }
}
