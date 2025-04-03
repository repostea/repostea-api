<?php

namespace App\Observers\ActivityPub;

use App\Models\Comment;

class CommentActivityPubObserver
{
    public function created(Comment $comment)
    {
        if (! $comment->user->is_remote) {
            dispatch(function () use ($comment) {
                $comment->federateToActivityPub();
            })->onQueue('activitypub');
        }
    }
}
