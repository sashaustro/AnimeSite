<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Отримуємо останні перегляди користувача
        $watchHistory = $user->watchHistories()->with(['anime', 'episode'])->take(10)->get();

        // Статистика списків
        $stats = [
            'watching' => $user->animeLists()->where('status', 'watching')->count(),
            'plan_to_watch' => $user->animeLists()->where('status', 'plan_to_watch')->count(),
            'completed' => $user->animeLists()->where('status', 'completed')->count(),
            'on_hold' => $user->animeLists()->where('status', 'on_hold')->count(),
            'dropped' => $user->animeLists()->where('status', 'dropped')->count(),
            'favorites' => $user->animeLists()->where('is_favorite', true)->count(),
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

        return view('dashboard', compact('watchHistory', 'stats', 'collections', 'user', 'watchDynamics'));
    }

    public function lists(Request $request)
    {
        $user = auth()->user();
        $status = $request->get('status', 'all');
        $sort = $request->get('sort', 'added_desc'); // added_desc, year_desc, title_asc, etc.

        $query = $user->animeLists()->with('anime');

        if ($status !== 'all') {
            if ($status === 'favorites') {
                $query->where('is_favorite', true);
            } else {
                $query->where('status', $status);
            }
        }

        // В простих цілях можна завантажити всі і відсортувати колекцію, або через join.
        // Оскільки ми хочемо сортувати по полях аніме, краще join або sortBy на колекції.
        $lists = $query->get();

        if ($sort === 'added_desc') {
            $lists = $lists->sortByDesc('created_at');
        } elseif ($sort === 'added_asc') {
            $lists = $lists->sortBy('created_at');
        } elseif ($sort === 'year_desc') {
            $lists = $lists->sortByDesc(fn($list) => $list->anime->year);
        } elseif ($sort === 'year_asc') {
            $lists = $lists->sortBy(fn($list) => $list->anime->year);
        } elseif ($sort === 'title_asc') {
            $lists = $lists->sortBy(fn($list) => $list->anime->title);
        } elseif ($sort === 'title_desc') {
            $lists = $lists->sortByDesc(fn($list) => $list->anime->title);
        }

        return view('profile.lists', compact('lists', 'status', 'sort'));
    }

    public function collections()
    {
        $user = auth()->user();
        $collections = $user->collections()->with('animes')->get();
        return view('profile.collections', compact('collections'));
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username,'.$user->id],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'age' => ['nullable', 'integer', 'min:1', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'profile_status' => ['nullable', 'string', 'max:80'],
        ]);

        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('username')) $user->username = $request->username;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('age')) $user->age = $request->age;
        if ($request->has('phone')) $user->phone = $request->phone;
        if ($request->has('profile_status')) $user->profile_status = $request->profile_status;

        if ($request->hasFile('avatar')) {
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();

        return Redirect::back()->with('success', 'Профіль успішно оновлено!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $userName = $user->username ?? $user->name;
        $userEmail = $user->email;

        // Schedule for deletion instead of immediate delete
        $user->update(['scheduled_for_deletion_at' => now()->addDays(7)]);

        try {
            \Illuminate\Support\Facades\Mail::to($userEmail)->send(new \App\Mail\AccountDeletionScheduledMail($userName));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send account deletion email: ' . $e->getMessage());
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
