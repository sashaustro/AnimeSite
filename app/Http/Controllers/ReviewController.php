<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Anime;
class ReviewController extends Controller
{
     public function store(Request $request, Anime $anime)
    {
        // Перевіряємо, щоб оцінка була від 1 до 10
        $request->validate([
            'rating' => 'required|integer|min:1|max:10',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Зберігаємо відгук
        Review::create([
            'user_id' => auth()->id(), // Беремо ID того, хто зараз авторизований
            'anime_id' => $anime->id,  // ID аніме, яке коментують
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Повертаємо користувача назад на сторінку аніме
        return back();
    }
}
