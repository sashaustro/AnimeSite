<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnimeList extends Model
{
    protected $fillable = [
        'user_id',
        'anime_id',
        'status',
        'is_favorite',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function anime()
    {
        return $this->belongsTo(Anime::class);
    }
}
