<?php

namespace App\Models;

use App\Services\ActivityPub\ActivityPubService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property User $user
 * @property Comment $parent
 * @property Collection|Link[] $link
 * @property Collection|Comment[] $replies
 */
class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'content',
        'user_id',
        'link_id',
    ];

    protected $casts = [
        'votes' => 'integer',
        'karma' => 'float',
        'federated' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function federateToActivityPub()
    {
        if (! (bool) config('activitypub.federation.enabled') || $this->federated) {
            return false;
        }

        $activityPubService = app(
            ActivityPubService::class);
        $result = $activityPubService->publishComment($this);

        if ($result) {
            $this->federated = true;
            $this->federated_at = now();
            $this->save();
        }

        return $result;
    }
}
