<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\HRLoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PersonalTestingSessionController extends Controller
{
    public function confidentialAgreement(): Response
    {
        return Inertia::render('PersonalTesting/ConfidentialAgreement');
    }

    public function create(): Response
    {
        return Inertia::render('PersonalTesting/AuthPage');
    }

    /**
     * @throws ValidationException
     */
    public function store(HRLoginRequest $request): RedirectResponse
    {
        $request->checkPermission();
        $user = User::where('login', $request['login'])->first();

        session(['PersonalTestingAdmin' => $user->id]);

        return redirect()->route('testing.index');
    }


    public function destroy(): RedirectResponse
    {
        session()->forget('PersonalTestingAdmin');
        return redirect()->route('login');
    }
}
