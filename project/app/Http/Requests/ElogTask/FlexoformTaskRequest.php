<?php

namespace App\Http\Requests\ElogTask;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FlexoformTaskRequest extends FormRequest
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
        return [
            'machine_id' => 'required|string|exists:machines,title',
            'print_machine_id' => 'required|string|exists:machines,title',
            'work_date' => 'required|date',
            'work_shift' => 'required|numeric',
            'master_id' => 'string|exists:workers,name',
            'collector_id' => 'string|exists:workers,name',
            'tkn' => 'string',
            'work_start' => 'date',
            'work_finish' => 'date',
            'work_fact' => 'numeric|nullable',
            'customer' => 'string|max:255',
            'print_title' => 'string|max:255',

            'design_number' => 'string|nullable',
            'streams' => 'numeric|nullable',
            'sleeves_diameter' => 'numeric|nullable',
            'sleeves_fact' => 'numeric',
            'paints_count' => 'numeric|nullable',
            'paints_and_sticky' => 'nullable',
            'new_forms' => 'string|nullable',
            'aniloks' => 'numeric|nullable',
            'unwind_direction' => 'string|nullable',
            'is_streams_distance_ok' => 'boolean',
            'is_mounting_dots_ok' => 'boolean',
            'notes' => 'string|max:1000|nullable|doesnt_start_with:=,!,<,>,@,#,$,%,^,&,*,<,>,?,:,;,~,{,},[,]',
        ];
    }

    public function attributes()
    {
        return [
            'machine_id' => 'Монтажное оборудование',
            'print_machine_id' => 'Печатная машина',
            'work_date' => 'Дата смены',
            'work_shift' => 'Смена',
            'master_id' => 'Мастер смены',
            'collector_id' => 'Сборщик',
            'tkn' => 'Номер карты',
            'work_start' => 'Начало работ',
            'work_finish' => 'Окончание работ',
            'work_fact' => 'Фактическое время выполнения',
            'customer' => 'Заказчик',
            'print_title' => 'Заказ',

            'design_number' => 'Номер дизайна',
            'sleeves_diameter' => 'Диаметр сливс',
            'sleeves_fact' => 'Факт сливс',
            'paints_count' => 'Количество красок',
            'new_forms' => 'Новые формы',
            'aniloks' => 'Чистка акнилоксов',

            'paints_and_sticky' => 'Липкая лента и краски',

            'unwind_direction' => 'Направление печати при размотке',
            'is_streams_distance_ok' => 'Расстояние между ручьями проверено',
            'notes' => "Примечание",
        ];
    }
}
