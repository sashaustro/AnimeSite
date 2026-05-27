<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, $animeId)
    {
        $request->validate([
            'score' => 'required|integer|min:1|max:10'
        ]);

        Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'anime_id' => $animeId],
            ['score' => $request->score]
        );

        return back()->with('success', 'Оцінку збережено!');
    }

    public function destroy($animeId)
    {
        Rating::where('user_id', Auth::id())->where('anime_id', $animeId)->delete();
        return back()->with('success', 'Оцінку скасовано.');
    }
}
