<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use BelongsToTenant, HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'bio',
        'avatar',
        'lang',
        'tenant_id',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'admin' => 'boolean',
            'moderator' => 'boolean',
            'verified' => 'boolean',
            'karma' => 'float',
            'level' => 'integer',
            'last_active_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($user) {
            $user->last_active_at = now();
        });
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getPublishedLinksAttribute()
    {
        return $this->links()
            ->where('status', 'published')
            ->count();
    }

    public function getPublicationRateAttribute(): float|int
    {
        $total = $this->links()->count();
        if ($total === 0) {
            return 0;
        }

        $published = $this->published_links;

        return round(($published / $total) * 100, 1);
    }

    public function hasVoted(Link $link)
    {
        return $this->votes()
            ->where('link_id', $link->id)
            ->exists();
    }

    public function getKarmaRankAttribute()
    {
        return User::where('karma', '>', $this->karma)->count() + 1;
    }
}
