<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function toggleRole(User $user)
    {
        // Не можна змінити роль самому собі (щоб не втратити адмінку)
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Ви не можете змінити роль самому собі.');
        }

        $user->role = $user->role === 'admin' ? 'user' : 'admin';
        $user->save();

        return back()->with('success', 'Роль користувача оновлено!');
    }
}
