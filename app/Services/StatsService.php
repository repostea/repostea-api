<?php

namespace App\Services;

use App\Models\Link;
use App\Models\User;
use Carbon\Carbon;

class StatsService
{
    public function getSiteStats(): array
    {
        $stats = [
            'total_users' => User::query()->count(),
            'active_users' => User::query()->where('last_active_at', '>=', Carbon::now()->subDays(7))->count(),
            'total_links' => Link::query()->count(),
            'published_links' => Link::query()->where('status', 'published')->count(),
            'pending_links' => Link::query()->where('status', 'pending')->count(),
            'links_today' => Link::query()->where('created_at', '>=', Carbon::now()->startOfDay())->count(),
            'published_today' => Link::query()->where('status', 'published')
                ->where('promoted_at', '>=', Carbon::now()->startOfDay())
                ->count(),
        ];

        return $stats;
    }

    public function getUserStats(User $user)
    {
        $stats = [
            'total_links' => $user->links()->count(),
            'published_links' => $user->links()->where('status', 'published')->count(),
            'karma_rank' => $user->karma_rank,
            'total_votes_cast' => $user->votes()->count(),
            'total_comments' => $user->comments()->count(),
            'publication_rate' => $user->publication_rate,
            'account_age_days' => $user->created_at->diffInDays(),
        ];

        return $stats;
    }
}
