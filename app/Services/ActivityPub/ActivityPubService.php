<?php

namespace App\Services\ActivityPub;

use App\Models\Comment;
use App\Models\Link;
use Illuminate\Support\Facades\Log;

class ActivityPubService
{
    /**
     * Publish a new link to the fediverse
     */
    public function publishLink(Link $link)
    {
        Log::info('Pending ActivityPub API call - not yet implemented.');

        return false;
    }

    /**
     * Publish a new comment to the fediverse
     */
    public function publishComment(Comment $comment)
    {
        Log::info('Pending ActivityPub API call - not yet implemented.');

        return false;
    }
}
