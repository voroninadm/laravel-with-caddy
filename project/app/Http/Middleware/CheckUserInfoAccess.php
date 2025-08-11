<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserInfoAccess
{
    /**
     * Проверяем, разрешен ли доступ пользователю к объявлениям сектора
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        $requestedSector = $request->route('sector');
        if (auth()->user()->sectors->contains('name', $requestedSector)) {
            return $next($request);
        }
        return abort(403, 'У вас нет доступа к этому участку');

    }
}
