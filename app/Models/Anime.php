<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 */
class Anime extends Model
{
    protected $fillable = [
        'title',
        'original_title',
        'description',
        'image',
        'studio',
        'voice_acting',
        'status',
        'year',
        'format',
        'country',
        'total_episodes',
        'duration',
        'broadcast_day',
        'broadcast_time',
        'source',
        'author',
        'season'
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class)->orderBy('episode_number', 'asc');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'anime_genre', 'anime_id', 'genre_id');
    }

    public function userLists()
    {
        return $this->hasMany(UserAnimeList::class);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_anime');
    }
}
