<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $animeId)
    {
        if (Auth::user()->isMuted()) {
            return back()->with('error', 'Ви не можете залишати коментарі до ' . Auth::user()->muted_until->format('d.m.Y H:i') . '. Причина: ' . (Auth::user()->mute_reason ?? 'Порушення правил'));
        }

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

    public function update(Request $request, Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            abort(403);
        }

        $request->validate([
            'body' => 'required|string|max:1000'
        ]);

        $comment->update([
            'body' => $request->body
        ]);

        return back()->with('success', 'Коментар оновлено!');
    }

    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $comment->delete();
        return back()->with('success', 'Коментар видалено.');
    }

    public function vote(Request $request, Comment $comment)
    {
        $request->validate([
            'vote' => 'required|in:1,-1'
        ]);

        $voteValue = (int) $request->vote;
        $userId = Auth::id();

        // Не можна голосувати за свій коментар
        if ($userId === $comment->user_id) {
            return response()->json(['error' => 'Ви не можете голосувати за свій коментар'], 400);
        }

        $existingVote = CommentVote::where('user_id', $userId)->where('comment_id', $comment->id)->first();
        $author = $comment->user;

        $finalVote = $voteValue;

        if ($existingVote) {
            if ($existingVote->vote === $voteValue) {
                // Відміна голосу
                $existingVote->delete();
                $author->reputation -= $voteValue;
                $finalVote = 0;
            } else {
                // Зміна голосу
                $existingVote->update(['vote' => $voteValue]);
                $author->reputation += ($voteValue * 2); // -1 -> 1 is +2, 1 -> -1 is -2
            }
        } else {
            // Новий голос
            CommentVote::create([
                'user_id' => $userId,
                'comment_id' => $comment->id,
                'vote' => $voteValue
            ]);
            $author->reputation += $voteValue;
        }

        $author->save();

        return response()->json([
            'rating' => $comment->rating,
            'userVote' => $finalVote
        ]);
    }
}
