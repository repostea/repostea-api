<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ApiRateLimiter
{
    public static function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            $clientId = $request->header('X-Repostea-Id');

            return Limit::perMinute(90)->by($clientId);
        });

        RateLimiter::for('strict-api', function (Request $request) {
            $clientId = $request->header('X-Repostea-Id');

            return Limit::perMinute(5)->by($clientId);
        });
    }
}
