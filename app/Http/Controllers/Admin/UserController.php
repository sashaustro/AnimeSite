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

        $sort = $request->input('sort', 'oldest');
        $sortDirection = $sort === 'newest' ? 'desc' : 'asc';

        $users = $query->orderBy('role', 'asc')->orderBy('id', $sortDirection)->paginate(20)->appends($request->all());
        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        // Не можна змінити роль самому собі (щоб не втратити адмінку)
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Ви не можете змінити роль самому собі.');
        }

        // Захист головного адміна
        if ($user->role === 'super_admin') {
            return back()->with('error', 'Ви не можете змінити роль головного адміністратора.');
        }

        $request->validate([
            'admin_password' => 'required|string',
            'new_role' => 'required|in:user,admin'
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->admin_password, auth()->user()->password)) {
            return back()->with('error', 'Невірний пароль адміністратора!');
        }

        $user->role = $request->new_role;
        $user->save();

        return back()->with('success', 'Роль користувача оновлено!');
    }

    public function destroy(User $user)
    {
        // Захист від видалення самого себе
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Ви не можете видалити власний акаунт з адмін-панелі.');
        }

        // Захист головного адміна
        if ($user->role === 'super_admin') {
            return back()->with('error', 'Ви не можете видалити головного адміністратора.');
        }

        $userName = $user->username ?? $user->name;
        $userEmail = $user->email;

        // Schedule for deletion instead of immediate delete
        $user->update(['scheduled_for_deletion_at' => now()->addDays(7)]);

        try {
            \Illuminate\Support\Facades\Mail::to($userEmail)->send(new \App\Mail\AccountDeletionScheduledMail($userName));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Could not send deletion email: ' . $e->getMessage());
        }

        return back()->with('success', 'Видалення користувача успішно заплановано (через 7 днів).');
    }

    public function restore(User $user)
    {
        if ($user->scheduled_for_deletion_at) {
            $user->update(['scheduled_for_deletion_at' => null]);
            return back()->with('success', 'Заплановане видалення скасовано. Користувач відновлений.');
        }
        return back();
    }

    public function forceDelete(User $user)
    {
        if (auth()->id() === $user->id || $user->role === 'super_admin') {
            return back()->with('error', 'Ви не можете видалити цього користувача.');
        }

        if ($user->scheduled_for_deletion_at) {
            $user->delete();
            return back()->with('success', 'Користувача остаточно видалено з бази даних.');
        }
        return back();
    }

    public function sendResetLink(User $user)
    {
        $status = \Illuminate\Support\Facades\Password::broker()->sendResetLink(
            ['email' => $user->email]
        );

        if ($status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
            return back()->with('success', 'Лист для відновлення пароля успішно надіслано користувачу.');
        }

        return back()->with('error', 'Не вдалося надіслати лист. Можливо, для цього email вже недавно надсилався лист.');
    }
}
