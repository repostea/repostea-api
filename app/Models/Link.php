<?php

namespace App\Models;

use App\Services\ActivityPub\ActivityPubService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property User $user
 * @property Collection|Tag[] $tags
 * @property Collection|Vote[] $votesList
 * @property Collection|Comment[] $comments
 * @property bool $is_remote
 */
class Link extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'url',
        'content',
        'description',
        'image',
        'nsfw',
        'votes',
        'karma',
        'is_remote',
    ];

    protected $casts = [
        'votes' => 'integer',
        'clicks' => 'integer',
        'karma' => 'float',
        'nsfw' => 'boolean',
        'federated' => 'boolean',
        'promoted_at' => 'datetime',
        'metadata' => 'array',
        'is_remote' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function votesList(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDiscarded($query)
    {
        return $query->where('status', 'discard');
    }

    public function federateToActivityPub()
    {
        if (! (bool) config('activitypub.federation.enabled') || $this->federated) {
            return false;
        }

        $activityPubService = app(ActivityPubService::class);
        $result = $activityPubService->publishLink($this);

        if ($result) {
            $this->federated = true;
            $this->federated_at = now();
            $this->save();
        }

        return $result;
    }
}
