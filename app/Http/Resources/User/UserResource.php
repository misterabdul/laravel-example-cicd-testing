<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Role\RoleCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $id
 * @property string $email
 * @property string $name
 * @property \DateTime|null $email_verified_at
 * @property string $password
 * @property \DateTime|null $created_at
 * @property \DateTime|null $updated_at
 * @property \DateTime|null $deleted_at
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Client> $clients
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Token> $tokens
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 */
class UserResource extends JsonResource
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
