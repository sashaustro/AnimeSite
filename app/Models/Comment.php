<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'anime_id', 'body'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function anime()
    {
        return $this->belongsTo(Anime::class);
    }

    public function votes()
    {
        return $this->hasMany(CommentVote::class);
    }

    public function getRatingAttribute()
    {
        return $this->votes()->sum('vote');
    }
}
