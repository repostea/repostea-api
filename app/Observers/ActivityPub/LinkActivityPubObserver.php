<?php

namespace App\Observers\ActivityPub;

use App\Models\Link;

class LinkActivityPubObserver
{
    public function created(Link $link)
    {
        if ($link->status === 'published' && ! $link->user->is_remote) {
            dispatch(function () use ($link) {
                $link->federateToActivityPub();
            })->onQueue('activitypub');
        }
    }

    public function updated(Link $link)
    {
        if ($link->isDirty('status') && $link->status === 'published' && ! $link->is_remote) {
            dispatch(function () use ($link) {
                $link->federateToActivityPub();
            })->onQueue('activitypub');
        }
    }
}
