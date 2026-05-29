<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 */
class Genre extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function animes()
    {
        return $this->belongsToMany(Anime::class, 'anime_genre', 'genre_id', 'anime_id');
    }
}