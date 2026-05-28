<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id', $request->search)
                  ->orWhere('name', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('role', 'asc')->orderBy('id', 'desc')->paginate(20)->appends($request->all());
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
