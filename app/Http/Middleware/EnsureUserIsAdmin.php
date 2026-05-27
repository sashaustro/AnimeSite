<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Перевіряємо: чи користувач залогінений ТА чи має роль 'admin'
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request);
        }

        // Якщо ні — видаємо помилку 403 (Доступ заборонено)
        abort(403, 'У вас немає прав адміністратора.');
    }
}