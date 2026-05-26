<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'anime_id', 'rating', 'comment'];

    // Відгук належить одному Користувачу [cite: 300]
    public function user()
    {
        return $this->belongsTo(User::class); 
    }

    // Відгук належить одному Аніме
    public function anime()
    {
        return $this->belongsTo(Anime::class);
    }
}
