<?php

namespace App\Http\Middleware\Elog;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PrintingTasksMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($this->checkPermissions($request)) {
            return $next($request);
        }
        return abort(403, 'Недостаточно прав или время изменения превышает допустимое');
    }

    private function checkPermissions(Request $request): bool
    {
        $user = Auth::user();

        $currentDateTime = now();
        $taskDateTime = $request->task->created_at;
        $timeDifference = $taskDateTime->diffInHours($currentDateTime);

        if ($user->hasPermissionTo('medium_printing_permission') && $timeDifference <= 48) {
            return true;
        }
        elseif ($user->hasPermissionTo('full_printing_permission')) {
                return true;
        }
        return false;
    }
}
