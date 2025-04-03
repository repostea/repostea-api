<?php

namespace App\Services;

use App\Models\Link;
use Carbon\Carbon;

class LinkPromotionService
{
    public function promoteLinks(): int
    {
        $minimumKarma = config('app.repostea.min_karma_for_promotion');
        $minimumTime = Carbon::now()->subHours(config('app.repostea.min_hours_for_promotion'));

        return TenantScopeManager::withoutGlobalTenantScope(function () use ($minimumKarma, $minimumTime) {
            $links = Link::pending()
                ->where('created_at', '<=', $minimumTime)
                ->where('karma', '>=', $minimumKarma)
                ->get();

            foreach ($links as $link) {
                $this->promoteLink($link);
            }

            return count($links);
        });
    }

    public function promoteLink(Link $link): Link
    {
        if (TenantScopeManager::isGlobalTenantScopeEnabled()) {
            return TenantScopeManager::withoutGlobalTenantScope(function () use ($link) {
                return $this->doPromoteLink($link);
            });
        }

        return $this->doPromoteLink($link);
    }

    private function doPromoteLink(Link $link): Link
    {
        $link->status = 'published';
        $link->promoted_at = Carbon::now();
        $link->save();

        $user = $link->user;
        $user->karma += 1;
        $user->save();

        return $link;
    }

    public function discardLink($linkId): Link
    {
        return TenantScopeManager::withoutGlobalTenantScope(function () use ($linkId) {
            $link = Link::findorFail($linkId);
            $link->status = 'discard';
            $link->save();

            return $link;
        });
    }
}
