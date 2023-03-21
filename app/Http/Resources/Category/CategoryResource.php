<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\Post\PostCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $id
 * @property string $slug
 * @property string $name
 * @property string|null $description
 * @property \DateTime|null $created_at
 * @property \DateTime|null $updated_at
 * @property \DateTime|null $deleted_at
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 */
class CategoryResource extends JsonResource
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
            'name'          => $this->name,
            'description'   => $this->description,
            'createdAt'     => $this->created_at?->format('c'),
            'updatedAt'     => $this->updated_at?->format('c'),
            'posts'         => new PostCollection($this->whenLoaded('posts')),
        ];
    }
}
