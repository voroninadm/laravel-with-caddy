<?php

namespace App\Http\Requests\ElogTask;

use Illuminate\Foundation\Http\FormRequest;

class LaminationTaskRequest extends FormRequest
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
            'student_id' => 'string|nullable|exists:workers,name',
            'helper_id'  => 'string|nullable|exists:workers,name',
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
            'mat2_id' => 'string|nullable|exists:materials,name',
            'covering_id' => 'string|nullable|exists:materials,name',
            'width1' => 'numeric|nullable',
            'width2' => 'numeric|nullable',
            'covering_width' => 'numeric|nullable',
            'thickness1' => 'numeric|nullable',
            'thickness2' => 'numeric|nullable',
            'covering_thickness' => 'numeric|nullable',
            'mat1count_plan' => 'numeric|nullable',
            'mat2count_plan' => 'numeric|nullable',
            'mat1count' => 'numeric|nullable',
            'mat2count' => 'numeric|nullable',
            'covering_count' => 'numeric|nullable',

            'workout_mass' => 'numeric|nullable',
            'workout_length' => 'numeric|nullable',
            'workout_m2' => 'numeric|nullable',
            'otk_mass' => 'numeric|nullable',
            'waste_plan' => 'numeric|nullable',
            'waste_print' => 'numeric|nullable',
            'waste_lam' => 'numeric|nullable',
            'waste_sum' => 'numeric|nullable',

            'prepare_hours' => 'numeric|nullable',
            'tech_clean' => 'numeric|nullable',
            'change_glue' => 'numeric|nullable',
            'electro' => 'numeric|nullable',
            'mechanical' => 'numeric|nullable',
            'tech_service' => 'numeric|nullable',
            'no_human' => 'numeric|nullable',
            'no_work' => 'numeric|nullable',
            'no_raw' => 'numeric|nullable',
            'short_downtime' => 'numeric|nullable',
            'total_downtime' => 'numeric|nullable',
            'no_reason_downtime' => 'numeric|nullable',
            'remain_perv' => 'numeric|nullable',
            'remain_sec' => 'numeric|nullable',
            'diff_circulation' => 'numeric|nullable',
            'prepare_ok' => 'boolean|nullable',
            'notes' => 'string|max:1000|nullable|doesnt_start_with:=,!,<,>,@,#,$,%,^,&,*,<,>,?,:,;,~,{,},[,]',
            'is_idle' => 'boolean',
            'is_complete' => 'boolean',
        ];
    }

    public function attributes()
    {
        return [
            'machine_id' => 'Ламинатор',
            'work_date' => 'Дата начала смены',
            'work_shift' => 'Рабочая смена',
            'master_id' => 'Мастер смены',
            'operator1_id' => 'Оператор 1',
            'operator2_id' => 'Оператор 2',
            'operator3_id' => 'Оператор 3',
            'student_id' => 'Ученик оператора',
            'helper_id' => 'Помощник оператора',
            'tkn' => 'ТКН',
            'work_plan' => 'Плановое время',
            'work_start' => 'Начало работ',
            'work_finish' => 'Окончание работ',
            'work_fact' => 'Факт работ',
            'work_productive' => 'Работа машины',
            'customer' => 'Заказчик',
            'print_title' => 'Заказ',
            'circulation' => 'Выработка',
            'mat1_id' => 'Материал 1',
            'mat2_id' => 'Материал 2',
            'covering_id' => 'Покрытие',
            'mat1count_plan' => 'План материала 1',
            'mat2count_plan' => 'План материала 2',
            'count' => 'Выработка материала 1',
            'mat2count' => 'Выработка материала 2',
            'covering_count' => 'Выработка покрытия',
            'width1' => 'Ширина 1',
            'width2' => 'Ширина 2',
            'covering_width' => 'Ширина покрытия',
            'thickness1' => 'Толщина 1',
            'thickness2' => 'Толщина 2',
            'covering_thickness' => 'Толщина покрытия',
            'workout_mass' => 'Выработка кг',
            'workout_length' => 'Выработка пог.м',
            'workout_m2' => 'Выработка м2',
            'otk_mass' => 'Выработка на ОТК',
            'waste_plan' => 'Отходы план',
            'waste_print' => 'Отходы печать',
            'waste_lam' => 'Отходы ламинации',
            'waste_sum' => 'Отходы общее',
            'prepare_mass' => 'Приправка кг',
            'prepare_hours' => 'Приправка час',
            'tech_clean' => 'Техническая чистка',
            'electro' => 'Простой электрика',
            'mechanical' => 'Простой механика',
            'change_glue' => 'Переклейка форм',
            'service_replacement' => 'Замена расходников',
            'tech_service' => 'Техобслуживание',
            'short_downtime' => 'Краткосрочные простои',
            'total_downtime' => 'Всего простоев',
            'no_reason_downtime' => 'Простой без причины',
            'remain_perv' => 'Остаток первички',
            'remain_sec' => 'Остаток вторички',
            'no_human' => 'Отсутствие людей',
            'no_work' => 'Отсутствие заказов',
            'no_raw' => 'Отсутствие сырья',
            'diff_circulation' => 'Разница тиража',
            'notes' => 'Примечание',
            'prepare_ok' => 'Приправка принята',
            'is_idle' => 'Простой всей смены',
            'is_complete' => 'Карта закрыта',
        ];
    }
}
