<?php

namespace App\Http\Requests\ElogTask;

use App\Models\Elog\Welding;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WeldingTaskRequest extends FormRequest
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
            'operator1_id' => 'string|nullable|exists:workers,name',
            'operator2_id' => 'string|nullable|exists:workers,name',
            'operator3_id' => 'string|nullable|exists:workers,name',
            'operator4_id' => 'string|nullable|exists:workers,name',
            'operator5_id' => 'string|nullable|exists:workers,name',
            'student1_id' => 'string|nullable|exists:workers,name',
            'student2_id' => 'string|nullable|exists:workers,name',
            'acceptor_id'  => 'string|nullable|exists:workers,name',
            'tkn' => 'string|nullable',
            'work_plan' => 'numeric|nullable',
            'work_start' => 'date|nullable',
            'work_finish' => 'date|nullable',
            'work_fact' => 'numeric|nullable',
            'customer' => 'string|nullable|max:255',
            'print_title' => 'string|nullable|max:255',
            'circulation' => 'numeric|nullable',

            'product_type' => ['nullable', Rule::in(array_values(config('constants.elog_welding_products')))],
            'mat1_id' => 'string|nullable|exists:materials,name',
            'mat2_id' => 'string|nullable|exists:materials,name',
            'mat3_id' => 'string|nullable|exists:materials,name',
            'product_width' => 'numeric|nullable',
            'thickness1' => 'numeric|nullable',
            'thickness2' => 'numeric|nullable',
            'thickness3' => 'numeric|nullable',
            'bottom' => 'numeric|nullable',
            'length' => 'numeric|nullable',
            'count_plan' => 'numeric|nullable',
            'count' => 'numeric|nullable',
            'bottom_type' => ['nullable', Rule::in(array_values(config('constants.elog_welding_pocket_bottoms')))],

            'is_pocket' => 'boolean|nullable',
            'is_handle' => 'boolean|nullable',
            'is_edge' => 'boolean|nullable',
            'is_perforation' => 'boolean|nullable',
            'is_ziplock' => 'boolean|nullable',

            'workout_count' => 'numeric|nullable',
            'workout_otk' => 'numeric|nullable',

            'waste_plan' => 'numeric|nullable',
            'waste_print' => 'numeric|nullable',
            'waste_edge' => 'numeric|nullable',
            'waste_welding' => 'numeric|nullable',
            'waste_sum' => 'numeric|nullable',

            'box_size_id' => ['nullable', Rule::in(array_values(config('constants.elog_welding_boxes')))],

            'electro' => 'numeric|nullable',
            'mechanical' => 'numeric|nullable',
            'speed' => 'numeric|nullable',
            'reconfig' => 'numeric|nullable',
            'calibrating' => 'numeric|nullable',
            'change_teflon' => 'numeric|nullable',
            'tech_service' => 'numeric|nullable',
            'no_human' => 'numeric|nullable',
            'no_work' => 'numeric|nullable',
            'no_raw' => 'numeric|nullable',
            'diff_circulation' => 'numeric|nullable',
            'prepare_ok' => 'boolean|nullable',
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
