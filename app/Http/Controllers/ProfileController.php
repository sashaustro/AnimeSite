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

        return view('dashboard', compact('watchHistory', 'stats', 'collections', 'user'));
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
        } elseif ($sort === 'year_desc') {
            $lists = $lists->sortByDesc(fn($list) => $list->anime->year);
        } elseif ($sort === 'title_asc') {
            $lists = $lists->sortBy(fn($list) => $list->anime->title);
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
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'age' => ['nullable', 'integer', 'min:1', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->age = $request->age;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();

        return Redirect::route('cabinet')->with('success', 'Профіль успішно оновлено!');
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

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
