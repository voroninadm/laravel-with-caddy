<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class DestroyUserController extends Controller
{
    public function destroy(User $id): RedirectResponse
    {
        $id->delete();
        return redirect()->back();
    }
}
