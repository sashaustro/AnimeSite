<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAnimeList;
use Illuminate\Support\Facades\Auth;

class UserListController extends Controller
{
    public function updateStatus(Request $request, $animeId)
    {
        $request->validate([
            'status' => 'nullable|string|in:watching,plan_to_watch,completed,on_hold,dropped',
        ]);

        $list = UserAnimeList::firstOrNew([
            'user_id' => Auth::id(),
            'anime_id' => $animeId,
        ]);

        $list->status = $request->status;
        
        // Якщо статус null і не в ізбраному, можна видалити запис, але поки що просто зберігаємо
        if ($list->status === null && !$list->is_favorite) {
            if ($list->exists) {
                $list->delete();
            }
        } else {
            $list->save();
        }

        return response()->json(['success' => true, 'status' => $list->status]);
    }

    public function toggleFavorite(Request $request, $animeId)
    {
        $list = UserAnimeList::firstOrNew([
            'user_id' => Auth::id(),
            'anime_id' => $animeId,
        ]);

        $list->is_favorite = !$list->is_favorite;
        
        if ($list->status === null && !$list->is_favorite) {
            if ($list->exists) {
                $list->delete();
            }
        } else {
            $list->save();
        }

        return response()->json(['success' => true, 'is_favorite' => $list->is_favorite]);
    }
}
