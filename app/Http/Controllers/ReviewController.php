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
        $review = Review::create([
            'user_id' => auth()->id(), // Беремо ID того, хто зараз авторизований
            'anime_id' => $anime->id,  // ID аніме, яке коментують
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Якщо запит надіслано через Fetch/Axios (AJAX)
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Відгук успішно додано!',
                'review' => $review->load('user') // Завантажуємо зв'язок з користувачем
            ], 201);
        }

        // Повертаємо користувача назад на сторінку аніме
        return back();
    }
}
