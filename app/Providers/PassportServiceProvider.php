<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class PassportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Passport::hashClientSecrets();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Passport::useAuthCodeModel(\App\Models\AuthCode::class);
        Passport::useClientModel(\App\Models\Client::class);
        Passport::usePersonalAccessClientModel(\App\Models\PersonalAccessClient::class);
        Passport::useRefreshTokenModel(\App\Models\RefreshToken::class);
        Passport::useTokenModel(\App\Models\Token::class);

        Passport::tokensExpireIn(Carbon::now()->addMinutes(60));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(15));
        Passport::personalAccessTokensExpireIn(Carbon::now()->addMonths(12));
    }
}
