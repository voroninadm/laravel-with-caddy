<?php

namespace App\Http\Requests\ElogTask;

use Illuminate\Foundation\Http\FormRequest;

class RecyclingTaskRequest extends FormRequest
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
            'machinist_id' => 'string|nullable|exists:workers,name',
            'helper_id' => 'string|nullable|exists:workers,name',
            'work_start' => 'date|nullable',
            'work_finish' => 'date|nullable',
            'work_fact' => 'numeric|nullable',
            'nomenclature'  => 'nullable|string|exists:materials,name',
            'workout_mass' => 'numeric|nullable',
            'ingots_type' => 'nullable|string|exists:materials,name',
            'waste_mass' => 'numeric|nullable',

            'prepare_hours' => 'numeric|nullable',
            'electro' => 'numeric|nullable',
            'mechanical' => 'numeric|nullable',
            'electronics' => 'numeric|nullable',

            'tech_service' => 'numeric|nullable',
            'change_net' => 'numeric|nullable',
            'tech_clean' => 'numeric|nullable',
            'clean_machine' => 'numeric|nullable',
            'change_knifes' => 'numeric|nullable',
            'change_cutter' => 'numeric|nullable',

            'no_human' => 'numeric|nullable',
            'no_work' => 'numeric|nullable',
            'no_raw' => 'numeric|nullable',
            'prepare_ok' => 'boolean|nullable',
            'notes' => 'string|max:1000|nullable|doesnt_start_with:=,!,<,>,@,#,$,%,^,&,*,<,>,?,:,;,~,{,},[,]',
            'is_idle' => 'boolean',
        ];
    }

    public function attributes()
    {
        return [
            'notes' => "Примечание",
        ];
    }
}
