<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'is_public',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function animes()
    {
        return $this->belongsToMany(Anime::class, 'collection_anime');
    }
}
