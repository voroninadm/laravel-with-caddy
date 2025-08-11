<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileRequest;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function users(): Response
    {
        $users = User::with('roles', 'sectors')
            ->where('id', '!=', Auth::id())
            ->where('login', '!=', 'admin')
            ->orderBy('name')
            ->get();
        return Inertia::render('Users/UsersPage', [
            'users' => $users
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Users/UsersCreate',
            ['sectors' => Sector::all()]);
    }

    public function find(User $id): Response
    {
        return $this->edit($id);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(UserProfileRequest $request): RedirectResponse
    {
        $validatedRequest = $request->validated();

        $user = User::create([
            'name' => $validatedRequest['name'],
            'email' => $validatedRequest['email'],
            'password' => Hash::make($validatedRequest['password']),
            'job' => $validatedRequest['job'],
            'login' => $validatedRequest['login'],
        ]);

        $user->assignRole($validatedRequest['role']);
        $user->permissions()->detach();
        $this->assignPermissionsToUser($user, $validatedRequest['permissions']);

        return Redirect::route('users.show');
    }

    public function edit(User $id)
    {
        $user = $id->load('roles', 'sectors');
        $id->getDirectPermissions();
        return Inertia::render('Users/UsersProfilePage', [
            'user' => $user,
            'sectors' => Sector::all()
        ]);
    }

    public function updateProfile(UserProfileRequest $request)
    {
        $validatedRequest = $request->validated();

        $user = User::find($validatedRequest['user_id']);
        $user->roles()->detach();
        $user->permissions()->detach();
        $user->assignRole($validatedRequest['role']);
        empty($validatedRequest['permissions']) ? $this->setDefaultPermissions($user) : $this->assignPermissionsToUser($user, $validatedRequest['permissions']);

        $user->update([
            'name' => $validatedRequest['name'],
            'email' => $validatedRequest['email'],
            'job' => $validatedRequest['job'],
        ]);

        if ($validatedRequest['infos_permissions']) {
            // Получаем массив ID секторов, которые имеют значение true
            $sectorIds = Sector::whereIn('name', array_keys(array_filter($validatedRequest['infos_permissions'])))
                ->pluck('id')
                ->toArray();

            // Привязываем пользователя к этим секторам
            $user->sectors()->sync($sectorIds);
        }

        return redirect()->route('users.show');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);
        $user = User::find($request->user_id);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('users.show');
    }

    private function assignPermissionsToUser($user, array $permissions): void
    {
        foreach ($permissions as $permission => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $user->givePermissionTo($permission);
                }
            } else {
                $user->givePermissionTo($value . '_' . $permission);
            }
        }
    }

    private function setDefaultPermissions($user)
    {
        $user->givePermissionTo([
            'basic_printing_permission',
            'basic_lamination_permission',
            'basic_welding_permission',
            'basic_cutting_permission',
            'basic_extrusion_permission',
            'basic_recycling_permission',
            'basic_upff_permission',

            'basic_printing_orders_permission',
            'basic_lamination_orders_permission',
            'basic_welding_orders_permission',
            'basic_cutting_orders_permission',
            'basic_extrusion_orders_permission',
            'basic_recycling_orders_permission',
            'basic_spoolcutting_orders_permission',
        ]);
    }
}
