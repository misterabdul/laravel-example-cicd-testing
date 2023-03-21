<?php

namespace App\Models;

use Laravel\Passport\RefreshToken as PassportRefreshToken;

/**
 * @property string $id
 * @property string $access_token_id
 * @property boolean $revoked
 * @property \DateTime|null $expires_at
 * @property \App\Models\Token $accessToken
 */
class RefreshToken extends PassportRefreshToken
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oauth_refresh_tokens';

    /**
     * Get the access token that the refresh token belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accessToken()
    {
        return $this->belongsTo(Token::class, 'access_token_id', 'id');
    }
}
