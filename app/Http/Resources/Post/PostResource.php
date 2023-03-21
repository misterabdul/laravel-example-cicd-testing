<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $id
 * @property string $category_id
 * @property string $user_id
 * @property string $slug
 * @property string $title
 * @property string $content
 * @property \DateTime|null $published_at
 * @property \DateTime|null $created_at
 * @property \DateTime|null $updated_at
 * @property \DateTime|null $deleted_at
 * @property \App\Models\Category $category
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 */
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
            'id'            => $this->id,
            'slug'          => $this->slug,
            'title'         => $this->title,
            'publishedAt'   => $this->published_at?->format('c'),
            'createdAt'     => $this->created_at?->format('c'),
            'updatedAt'     => $this->updated_at?->format('c'),
            'category'      => new CategoryResource($this->whenLoaded('category')),
            'user'          => new UserResource($this->whenLoaded('user')),
            'comments'      => new CommentCollection($this->whenLoaded('comments')),
        ];
    }
}
