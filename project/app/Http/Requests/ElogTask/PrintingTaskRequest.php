<?php

namespace App\Http\Requests\ElogTask;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PrintingTaskRequest extends FormRequest
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
            'operator_helper' => 'string|nullable|exists:workers,name',
            'tkn' => 'string|nullable',
            'work_plan' => 'numeric|nullable',
            'work_start' => 'date|nullable',
            'work_finish' => 'date|nullable',
            'work_fact' => 'numeric|nullable',
            'work_productive' => 'numeric|nullable',
            'customer' => 'string|nullable|max:255',
            'print_title' => 'string|nullable|max:255',
            'circulation' => 'numeric|nullable',
            'mat1_id' => 'string|nullable|exists:materials,name',
            'mat1count_plan' => 'numeric|nullable',
            'mat1count' => 'numeric|nullable',
            'width1' => 'numeric|nullable',
            'thickness1' => 'numeric|nullable',
            'colors' => 'numeric|nullable',
            'workout_mass' => 'numeric|nullable',
            'workout_length' => 'numeric|nullable',
            'workout_m2' => 'numeric|nullable',
            'otk_mass' => 'numeric|nullable',
            'waste_plan' => 'numeric|nullable',
            'waste_print' => 'numeric|nullable',
            'waste_raw' => 'numeric|nullable',
            'waste_sum' => 'numeric|nullable',
            'prepare_mass' => 'numeric|nullable',
            'prepare_hours' => 'numeric|nullable',
            'correction_PN' => 'numeric|nullable',
            'correction_CMYK' => 'numeric|nullable',
            'electro' => 'numeric|nullable',
            'mechanical' => 'numeric|nullable',
            'form_glue' => 'numeric|nullable',
            'service_replacement' => 'numeric|nullable',
            'tech_service' => 'numeric|nullable',
            'short_downtime' => 'numeric|nullable',
            'total_downtime' => 'numeric|nullable',
            'no_reason_downtime' => 'numeric|nullable',
            'speed' => 'numeric|nullable',
            'no_human' => 'numeric|nullable',
            'no_work' => 'numeric|nullable',
            'no_raw' => 'numeric|nullable',
            'diff_circulation' => 'numeric|nullable',
            'notes' => 'string|max:1000|nullable|doesnt_start_with:=,!,<,>,@,#,$,%,^,&,*,<,>,?,:,;,~,{,},[,]',
            'is_idle' => 'boolean',
            'is_complete' => 'boolean',
        ];
    }

    public function attributes()
    {
        return [
            'machine_id' => 'Машина',
            'work_date' => 'Дата начала смены',
            'work_shift' => 'Рабочая смена',
            'master_id' => 'Мастер смены',
            'operator1_id' => 'Оператор 1',
            'operator2_id' => 'Оператор 2',
            'operator3_id' => 'Оператор 3',
            'operator_helper' => 'Помощник оператора',
            'tkn' => 'ТКН',
            'work_plan' => 'Плановое время',
            'work_start' => 'Начало работ',
            'work_finish' => 'Окончание работ',
            'work_fact' => 'Факт работ',
            'work_productive' => 'Работа машины',
            'customer' => 'Заказчик',
            'print_title' => 'Заказ',
            'circulation' => 'Выработка',
            'mat1_id' => 'Материал',
            'mat1count_plan' => 'План материала',
            'mat1count' => 'Выработка материала',
            'width1' => 'Ширина',
            'thickness1' => 'Толщина',
            'colors' => 'Краски',
            'workout_mass' => 'Выработка кг',
            'workout_length' => 'Выработка пог.м',
            'workout_m2' => 'Выработка м2',
            'otk_mass' => 'Выработка на ОТК',
            'waste_plan' => 'Отходы план',
            'waste_print' => 'Отходы печать',
            'waste_raw' => 'Отходы сырье',
            'waste_sum' => 'Отходы общее',
            'prepare_mass' => 'Приправка кг',
            'prepare_hours' => 'Приправка час',
            'correction_PN' => 'Коррекция пантонов',
            'correction_CMYK' => 'Коррекция CMYK',
            'electro' => 'Простой электрика',
            'mechanical' => 'Простой механика',
            'form_glue' => 'Переклейка форм',
            'service_replacement' => 'Замена расходников',
            'tech_service' => 'Техобслуживание',
            'short_downtime' => 'Краткосрочные простои',
            'total_downtime' => 'Всего простоев',
            'no_reason_downtime' => 'Простой без причины',
            'speed' => 'Скорость',
            'no_human' => 'Отсутствие людей',
            'no_work' => 'Отсутствие заказов',
            'no_raw' => 'Отсутствие сырья',
            'diff_circulation' => 'Разница тиража',
            'notes' => 'Примечание',
            'is_idle' => 'Простой всей смены',
            'is_complete' => 'Карта закрыта',
        ];
    }
}
