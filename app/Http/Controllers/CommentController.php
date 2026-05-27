<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $animeId)
    {
        $request->validate([
            'body' => 'required|string|max:1000'
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'anime_id' => $animeId,
            'body' => $request->body
        ]);

        return back()->with('success', 'Коментар додано успішно!');
    }

    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $comment->delete();
        return back()->with('success', 'Коментар видалено.');
    }
}
