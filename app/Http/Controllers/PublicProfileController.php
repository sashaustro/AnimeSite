<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show($id)
    {
        $user = User::withCount('comments', 'ratings')->findOrFail($id);
        
        // Отримуємо останні перегляди користувача
        $watchHistory = $user->watchHistories()->with(['anime', 'episode'])->take(10)->get();

        $stats = [
            'watching' => $user->animeLists()->where('status', 'watching')->count(),
            'planned' => $user->animeLists()->where('status', 'plan_to_watch')->count(),
            'completed' => $user->animeLists()->where('status', 'completed')->count(),
            'on_hold' => $user->animeLists()->where('status', 'on_hold')->count(),
            'dropped' => $user->animeLists()->where('status', 'dropped')->count(),
            'total_episodes' => $user->animeLists()->where('status', 'completed')->with('anime')->get()->sum(function($item) {
                return $item->anime->total_episodes ?? 0;
            })
        ];
        
        $collections = $user->collections()->withCount('animes')->get();

        // Динаміка перегляду серій за останні 7 днів
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $last7Days->put($date, 0);
        }

        $historyData = $user->watchHistories()
            ->where('updated_at', '>=', now()->subDays(6)->startOfDay())
            ->get()
            ->groupBy(function($item) {
                return $item->updated_at->format('Y-m-d');
            });

        foreach ($historyData as $date => $items) {
            if ($last7Days->has($date)) {
                $last7Days[$date] = $items->count();
            }
        }
        
        $watchDynamics = [
            'labels' => $last7Days->keys()->map(fn($d) => \Carbon\Carbon::parse($d)->format('d.m'))->values()->toArray(),
            'data' => $last7Days->values()->toArray()
        ];

        return view('profile.public', compact('user', 'stats', 'collections', 'watchHistory', 'watchDynamics'));
    }
}
