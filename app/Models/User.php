<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'profile_status',
        'reputation',
        'muted_until',
        'mute_reason',
        'scheduled_for_deletion_at'
    ];

    protected static function booted()
    {
        static::deleting(function ($user) {
            $user->comment_votes()->delete();
            $user->reports()->delete();
            $user->watchHistories()->delete();
            $user->reviews()->delete();
            $user->comments()->delete();
            $user->ratings()->delete();
            $user->animeLists()->delete();
            $user->collections()->each(function ($collection) {
                $collection->delete(); // This handles collection_anime detachment if needed
            });
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
        });
    }

    public function comment_votes()
    {
        return $this->hasMany(CommentVote::class);
    }


    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function getPositiveVotesAttribute()
    {
        return \App\Models\CommentVote::whereHas('comment', function($query) {
            $query->where('user_id', $this->id);
        })->where('vote', 1)->count();
    }

    public function getNegativeVotesAttribute()
    {
        return \App\Models\CommentVote::whereHas('comment', function($query) {
            $query->where('user_id', $this->id);
        })->where('vote', -1)->count();
    }

    public function isMuted()
    {
        return $this->muted_until && $this->muted_until->isFuture();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'muted_until' => 'datetime',
            'scheduled_for_deletion_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function watchHistories()
    {
        return $this->hasMany(WatchHistory::class)->orderBy('watched_at', 'desc');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function animeLists()
    {
        return $this->hasMany(UserAnimeList::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
}
