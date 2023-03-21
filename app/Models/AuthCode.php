<?php

namespace App\Models;

use Laravel\Passport\AuthCode as PassportAuthCode;

/**
 * @property string $id
 * @property string $user_id
 * @property string $client_id
 * @property string|null $scopes
 * @property \DateTime|null $expires_at
 * @property \App\Models\User $user
 * @property \App\Models\Client $client
 */
class AuthCode extends PassportAuthCode
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oauth_auth_codes';

    /**
     * Get the user that owns the authentication code.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the client that owns the authentication code.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}
