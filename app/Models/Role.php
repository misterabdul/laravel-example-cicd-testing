<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property \DateTime|null $created_at
 * @property \DateTime|null $updated_at
 * @property \DateTime|null $deleted_at
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 */
class Role extends Model
{
    use HasUuids,
        SoftDeletes;

    /**
     * @var string
     */
    public const ROLE_ADMIN = 'admin';

    /**
     * @var string
     */
    public const ROLE_EDITOR = 'editor';

    /**
     * @var string
     */
    public const ROLE_WRITER = 'writer';

    /**
     * @var string
     */
    public const ROLE_USER = 'user';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The users that belong to the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            UserRole::class,
            'role_id',
            'user_id',
            'id',
            'id'
        );
    }
}
