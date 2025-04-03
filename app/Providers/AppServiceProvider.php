<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Link;
use App\Observers\ActivityPub\CommentActivityPubObserver;
use App\Observers\ActivityPub\LinkActivityPubObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ApiRateLimiter::boot();

        Link::observe(LinkActivityPubObserver::class);
        Comment::observe(CommentActivityPubObserver::class);
    }
}
