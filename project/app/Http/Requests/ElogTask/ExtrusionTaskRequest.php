<?php

namespace App\Http\Requests\ElogTask;

use App\Models\Elog\Extrusion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExtrusionTaskRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'machine_id' => 'required|string|exists:machines,title',
            'work_date' => 'required|date',
            'work_shift' => 'required|numeric',
            'master_id' => 'string|exists:workers,name',
            'machinist1_id' => 'string|nullable|exists:workers,name',
            'machinist2_id' => 'string|nullable|exists:workers,name',
            'machinist3_id' => 'sometimes|string|nullable|exists:workers,name',
            'student_id' => 'string|nullable|exists:workers,name',

            'tkn' => 'string|nullable',
            'recipe_number' => 'numeric|nullable',
            'part_number' => 'string|nullable',
            'work_plan' => 'numeric|nullable',
            'work_start' => 'date|nullable',
            'work_finish' => 'date|nullable',
            'work_fact' => 'numeric|nullable',
            'customer' => 'string|nullable|max:255',
            'print_title' => 'string|nullable|max:255',
            'circulation_kg' => 'numeric|nullable',
            'circulation_length' => 'numeric|nullable',

            'product_type' => ['nullable', Rule::in(array_values(config('constants.elog_extrusion_products')))],
            'width' => 'numeric|nullable',
            'thickness' => 'numeric|nullable',
            'activation_pole' => 'numeric|nullable',
            'edge' => 'numeric|nullable',
            'streams' => 'numeric|nullable',

            'roll_mass' => 'numeric|nullable',
            'roll_length' => 'numeric|nullable',
            'roll_diameter' => 'numeric|nullable',

            'workout_mass' => 'numeric|nullable',
            'workout_otk' => 'numeric|nullable',
            'workout_length' => 'numeric|nullable',
            'workout_m2' => 'numeric|nullable',

            'waste_plan_roll' => 'numeric|nullable',
            'waste_roll' => 'numeric|nullable',
            'waste_trans' => 'numeric|nullable',
            'waste_plan_edge' => 'numeric|nullable',
            'waste_edge' => 'numeric|nullable',
            'waste_plan_ingets' => 'numeric|nullable',
            'waste_ingets' => 'numeric|nullable',
            'waste_slice' => 'numeric|nullable',
            'waste_sum' => 'numeric|nullable',

            'electro' => 'numeric|nullable',
            'mechanical' => 'numeric|nullable',
            'electronics' => 'numeric|nullable',
            'tech_service' => 'numeric|nullable',
            'change_net' => 'numeric|nullable',
            'change_raw' => 'numeric|nullable',
            'change_order' => 'numeric|nullable',
            'clean_machine' => 'numeric|nullable',
            'no_human' => 'numeric|nullable',
            'no_work' => 'numeric|nullable',
            'no_raw' => 'numeric|nullable',
            'prepare_hours' => 'numeric|nullable',
            'prepare_ok' => 'boolean|nullable',
            'diff_circulation' => 'numeric|nullable',
            'notes' => 'string|max:1000|nullable|doesnt_start_with:=,!,<,>,@,#,$,%,^,&,*,<,>,?,:,;,~,{,},[,]',
            'is_idle' => 'boolean'
        ];
    }

    public function attributes()
    {
        return [
            'notes' => "Примечание",
        ];
    }
}
