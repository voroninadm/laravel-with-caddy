<?php

namespace App\Http\Middleware\PersonalTesting;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTestsAuth
{
    /**
     * Проверяем, создана ли сессия для HR
     *  Если сессия не создана, переадресовываем на авторизацию отдела кадров
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем, есть ли пользователь в сессии
        if (!session('PersonalTestingAdmin')) {
            return redirect()->route('testing.auth');
        }

        return $next($request);
    }
}
