<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'studio',
        'voice_acting',
        'status',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'anime_genre', 'anime_id', 'genre_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }
}
