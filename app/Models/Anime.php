<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    protected $fillable = [
        'title',
        'genre',
        'description',
        'image',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
