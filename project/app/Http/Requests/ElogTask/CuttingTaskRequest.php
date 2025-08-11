<?php

namespace App\Http\Requests\ElogTask;

use App\Models\Elog\Cutting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CuttingTaskRequest extends FormRequest
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
            'operator_id' => 'string|nullable|exists:workers,name',
            'packer_id' => 'string|nullable|exists:workers,name',
            'student_id' => 'string|nullable|exists:workers,name',
            'tkn' => 'string|nullable',
            'work_plan' => 'numeric|nullable',
            'work_start' => 'date|nullable',
            'work_finish' => 'date|nullable',
            'work_fact' => 'numeric|nullable',
            'customer' => 'string|nullable|max:255',
            'print_title' => 'string|nullable|max:255',
            'circulation' => 'numeric|nullable',

            'product_type' => ['nullable', Rule::in(array_values(config('constants.elog_cutting_products')))],
            'mat1_id' => 'string|nullable|exists:materials,name',
            'mat2_id' => 'string|nullable|exists:materials,name',
            'mat3_id' => 'string|nullable|exists:materials,name',
            'thickness1' => 'numeric|nullable',
            'thickness2' => 'numeric|nullable',
            'thickness3' => 'numeric|nullable',
            'canvas_width' => 'numeric|nullable',
            'count_plan' => 'numeric|nullable',
            'count' => 'numeric|nullable',
            'streams' => 'numeric|nullable',
            'stream_width' => 'numeric|nullable',

            'workout_mass' => 'numeric|nullable',
            'otk_mass' => 'numeric|nullable',
            'workout_length' => 'numeric|nullable',
            'workout_m2' => 'numeric|nullable',
            'raw_meters' => 'numeric|nullable',

            'waste_plan' => 'numeric|nullable',
            'waste_print' => 'numeric|nullable',
            'waste_lam' => 'numeric|nullable',
            'waste_edge' => 'numeric|nullable',
            'waste_sum' => 'numeric|nullable',

            'electro' => 'numeric|nullable',
            'mechanical' => 'numeric|nullable',
            'speed' => 'numeric|nullable',
            'tech_service' => 'numeric|nullable',
            'knifes_barbell' => 'numeric|nullable',
            'reconfig' => 'numeric|nullable',
            'no_human' => 'numeric|nullable',
            'no_work' => 'numeric|nullable',
            'no_raw' => 'numeric|nullable',
            'prepare_ok' => 'boolean|nullable',
            'diff_circulation' => 'numeric|nullable',
            'notes' => 'string|max:1000|nullable|doesnt_start_with:=,!,<,>,@,#,$,%,^,&,*,<,>,?,:,;,~,{,},[,]',
            'is_idle' => 'boolean'
        ];
    }

    public function attributes()
    {
        return [
            'machine_id' => 'Машина',
            'work_date' => 'Дата смены',
            'work_shift' => 'Смена',
            'master_id' => 'Мастер',
            'operator_id' => 'Оператор',
            'packer_id' => 'Упаковщик',
            'student_id' => 'Ученик',
            'tkn' => 'Номер карты',
            'work_plan' => 'Плановое время',
            'work_start' => 'Начало работ',
            'work_finish' => 'Окончание работ',
            'work_fact' => 'Факт работ',
            'customer' => 'Заказчик',
            'print_title' => 'Заказ',
            'circulation' => 'Выработка',

            'product_type' => 'Тип продукта',
            'mat1_id' => 'Материал 1',
            'mat2_id' => 'Материал 2',
            'mat3_id' => 'Материал 3',
            'thickness1' => 'Толщина 1',
            'thickness2' => 'Толщина 2',
            'thickness3' => 'Толщина 3',
            'canvas_width' => 'Ширина полотна',
            'count_plan' => 'План выработки',
            'count' => 'Выработка',
            'streams' => 'Ручьи',
            'stream_width' => 'Ширина ручья',

            'workout_mass' => 'Выработка кг',
            'otk_mass' => 'На ОТК',
            'workout_length' => 'Выработка пог.м.',
            'workout_m2' => 'Выработка м2',
            'raw_meters' => 'Сырьевые метры',

            'waste_plan' => 'Отходы план',
            'waste_print' => 'Отходы печать',
            'waste_lam' => 'Отходы ламинация',
            'waste_edge' => 'Отходы кромка',
            'waste_sum' => 'Отходы итог',

            'electro' => 'Электрика',
            'mechanical' => 'Механика',
            'speed' => 'Скорость',
            'tech_service' => 'Техобслуживание',
            'knifes_barbell' => 'Замена ножей',
            'reconfig' => 'Перестройка',
            'no_human' => 'отсутствие людей',
            'no_work' => 'Отсутствие работ',
            'no_raw' => 'Отсутствие сырья',
            'prepare_ok' => 'Приправка принята',
            'diff_circulation' => 'Разница тиража',
            'notes' => "Примечание",
        ];
    }
}
