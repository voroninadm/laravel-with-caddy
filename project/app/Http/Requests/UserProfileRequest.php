<?php

namespace App\Http\Requests;

use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rules;


class UserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $permission_levels = ['basic', 'medium', 'full'];
        $validNames = Sector::all()->pluck('name')->toArray();

        return [
            'user_id' => 'sometimes|integer',
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user_id)],
            'job' => 'string|nullable|max:255',
            'password' => ['sometimes', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|string|exists:roles,name',
            'login' => 'sometimes|string|max:255|unique:' . User::class,
            'permissions' => 'required',
            'permissions.set_permissions' => 'boolean',
            'permissions.open_users_profiles' => 'boolean',
            'permissions.open_workers_profiles' => 'boolean',
            'permissions.open_apps' => 'boolean',
            'permissions.open_tests' => 'boolean',
            'permissions.printing_*' => [
                'required',
                Rule::in($permission_levels),
            ],
            'permissions.lamination_*' => [
                'required',
                Rule::in($permission_levels),
            ],
            'permissions.welding_*' => [
                'required',
                Rule::in($permission_levels),
            ],
            'permissions.cutting_*' => [
                'required',
                Rule::in($permission_levels),
            ],
            'permissions.extrusion_*' => [
                'required',
                Rule::in($permission_levels),
            ],
            'permissions.recycling_*' => [
                'required',
                Rule::in($permission_levels),
            ],
            'permissions.upff_*' => [
                'required',
                Rule::in($permission_levels),
            ],
            'permissions.spoolcutting_orders_permission' => [
                'required',
                Rule::in($permission_levels),
            ],
            'infos_permissions.*' => ['boolean'], // Каждое значение должно быть true/false
            'infos_permissions' => [
                'required',
                'array',
                function ($attribute, $value, $fail) use ($validNames) {
                    $invalidKeys = array_diff(array_keys($value), $validNames);
                    if (!empty($invalidKeys)) {
                        $fail('Следующие участки отсутствуют в базе: ' . implode(', ', $invalidKeys));
                    }
                },
            ],

        ];
    }
}
