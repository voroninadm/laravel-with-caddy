<?php

namespace App\Http\Middleware\PersonalTesting;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTestsNotAuth
{
    /**
     * Проверяем, создана ли сессия для HR
     * Если сессия создана, переадресовываем на главную по тестам
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем, есть ли пользователь в сессии
        if (session('PersonalTestingAdmin')) {
            return redirect()->route('testing.index');
        }

        return $next($request);
    }
}
