<?php

use App\Models\Elog\Lamination;

it('can make new Lamination tasks', function () {
    $request = Lamination::create([
        'machine_id' => 'Ламинатор 1',
        'work_shift' => 1,
        'work_date' => '2025-01-01',
        'master_id' => 'Поцко С.С.',
        'operator1_id' => 'Гизатулин А.Р.',
        'operator2_id' => 'Юркевич В.В.',
        'operator3_id' => null,
        'student_id' => null,
        'helper_id' => null,
        'tkn' => '12345a',
        'work_plan' => 100.00,
        'work_start' => '2025-01-01 08:00:00',
        'work_finish' => '2025-01-01 16:00:00',
        'work_fact' => 95.00,
        'customer' => 'Тестовый заказчик',
        'print_title' => 'Тестовый заказ',
        'circulation' => 500.00,
        'mat1_id' => 'BOPP',
        'mat2_id' => null,
        'mat3_id' => null,
        'width1' => 210.00,
        'width2' => null,
        'width3' => null,
        'thickness1' => 10.00,
        'thickness2' => null,
        'thickness3' => null,
        'mat1count_plan' => 100.00,
        'mat2count_plan' => null,
        'mat3count_plan' => null,
        'mat1count' => 95.00,
        'mat2count' => null,
        'mat3count' => null,
        'workout_mass' => 10.00,
        'workout_length' => 200.00,
        'workout_m2' => 400.00,
        'otk_mass' => 5.00,
        'waste_plan' => 10.00,
        'waste_print' => 5.00,
        'waste_lam' => 5.00,
        'waste_sum' => 15.00,
        'prepare' => null,
        'prepare_shirt' => null,
        'flushing' => null,
        'tech_clean' => null,
        'change_glue' => null,
        'electro' => null,
        'mechanical' => null,
        'tech_service' => null,
        'calibrating' => null,
        'no_human' => null,
        'no_work' => null,
        'no_raw' => null,
        'remain_perv' => null,
        'remain_sec' => null,
        'diff_circulation' => 1221,
        'prepare_ok' => null,
        'notes' => 'Тестовая нормальная смена',
        'is_idle' => 0
    ]);

    $lamination = Lamination::find($request->id);

    expect($lamination->id)->toBeInt()
        ->and($lamination->machine_id)->toBeString()
        ->and($lamination->work_shift)->toBeInt()
        ->and($lamination->work_date)->toBeString()
        ->and($lamination->master_id)->toBeString()
        ->and($lamination->operator1_id)->toBeString()
        ->and($lamination->operator2_id)->toBeString()
        ->and($lamination->operator3_id)->toBeEmpty()
        ->and($lamination->student_id)->toBeEmpty()
        ->and($lamination->helper_id)->toBeEmpty()
        ->and($lamination->tkn)->toBeString()
        ->and($lamination->work_plan)->toBeFloat()
        ->and($lamination->work_start)->toBeString()
        ->and($lamination->work_finish)->toBeString()
        ->and($lamination->work_fact)->toBeFloat()
        ->and($lamination->customer)->toBeString()
        ->and($lamination->print_title)->toBeString()
        ->and($lamination->circulation)->toBeFloat()
        ->and($lamination->mat1_id)->toBeString()
        ->and($lamination->mat2_id)->toBeEmpty()
        ->and($lamination->mat3_id)->toBeEmpty()
        ->and($lamination->width1)->toBeFloat()
        ->and($lamination->width2)->toBeEmpty()
        ->and($lamination->width3)->toBeEmpty()
        ->and($lamination->thickness1)->toBeFloat()
        ->and($lamination->thickness2)->toBeEmpty()
        ->and($lamination->thickness3)->toBeEmpty()
        ->and($lamination->mat1count_plan)->toBeFloat()
        ->and($lamination->mat2count_plan)->toBeEmpty()
        ->and($lamination->mat3count_plan)->toBeEmpty()
        ->and($lamination->mat1count)->toBeFloat()
        ->and($lamination->mat2count)->toBeEmpty()
        ->and($lamination->mat3count)->toBeEmpty()
        ->and($lamination->workout_mass)->toBeFloat()
        ->and($lamination->workout_length)->toBeFloat()
        ->and($lamination->workout_m2)->toBeFloat()
        ->and($lamination->otk_mass)->toBeFloat()
        ->and($lamination->waste_plan)->toBeFloat()
        ->and($lamination->waste_print)->toBeFloat()
        ->and($lamination->waste_lam)->toBeFloat()
        ->and($lamination->waste_sum)->toBeFloat()
        ->and($lamination->prepare)->toBeEmpty()
        ->and($lamination->prepare_shirt)->toBeEmpty()
        ->and($lamination->flushing)->toBeEmpty()
        ->and($lamination->tech_clean)->toBeEmpty()
        ->and($lamination->change_glue)->toBeEmpty()
        ->and($lamination->electro)->toBeEmpty()
        ->and($lamination->mechanical)->toBeEmpty()
        ->and($lamination->tech_service)->toBeEmpty()
        ->and($lamination->calibrating)->toBeEmpty()
        ->and($lamination->no_human)->toBeEmpty()
        ->and($lamination->no_work)->toBeEmpty()
        ->and($lamination->no_raw)->toBeEmpty()
        ->and($lamination->remain_perv)->toBeEmpty()
        ->and($lamination->remain_sec)->toBeEmpty()
        ->and($lamination->diff_circulation)->toBeFloat()
        ->and($lamination->prepare_ok)->toBeEmpty()
        ->and($lamination->notes)->toBeString()
        ->and($lamination->is_idle)->toBeInt();
})->group('machinesTaskTests');
