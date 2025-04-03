<?php

namespace App\Services;

use App\Models\User;

class KarmaService
{
    public function updateUserKarma(User $user)
    {
        $publishedLinks = $user->links()->where('status', 'published')->count();
        $totalVotesReceived = $user->links()->get()->sum('votes');
        $commentCount = $user->comments()->count();

        $newKarma = 6.0;
        $newKarma += min($publishedLinks * 0.1, 2);
        $newKarma += min($totalVotesReceived * 0.01, 3);
        $newKarma += min($commentCount * 0.01, 1);

        $discardedLinks = $user->links()->where('status', 'discard')->count();
        $newKarma -= min($discardedLinks * 0.2, 2);

        $newKarma = round(max(1, min($newKarma, 20)), 1);

        $user->karma = $newKarma;
        $user->save();

        return $newKarma;
    }

    public function updateAllUsersKarma()
    {
        $users = User::all();

        foreach ($users as $user) {
            $this->updateUserKarma($user);
        }

        return count($users);
    }
}
