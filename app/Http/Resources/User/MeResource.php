<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Role\RoleCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'email'     => $this->email,
            'name'      => $this->name,
            'createdAt' => $this->created_at?->format('c'),
            'updatedAt' => $this->updated_at?->format('c'),
            'roles'     => new RoleCollection($this->whenLoaded('roles')),
            'posts'     => new PostCollection($this->whenLoaded('posts')),
        ];
    }
}
