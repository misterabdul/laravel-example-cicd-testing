<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

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
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 */
class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        HasUuids,
        Notifiable,
        SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'name',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['roles'];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return \Database\Factories\UserFactory::new();
    }

    /**
     * Get all of the clients that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients()
    {
        return $this->hasMany(Client::class, 'user_id', 'id');
    }

    /**
     * Get all of the tokens that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany(Token::class, 'user_id', 'id');
    }

    /**
     * The users that belong to the role.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            UserRole::class,
            'user_id',
            'role_id',
            'id',
            'id'
        );
    }

    /**
     * Get all of the posts that belong to the user.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    /**
     * Get all of the comments that belong to the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    /**
     * Check whether user is part of the admin role or not.
     */
    public function hasRole(string $name): bool
    {
        return $this->roles->contains(function (Role $role, int $key) use ($name) {
            return $role->name === $name;
        });
    }
}
