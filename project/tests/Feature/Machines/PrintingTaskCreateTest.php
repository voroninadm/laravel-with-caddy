<?php

use App\Models\Elog\Printing;

it('can make new Printing tasks', function () {
    $request = Printing::create([
        'machine_id' => 'Allstein',
        'work_shift' => 1,
        'work_date' => '2025-01-01',
        'master_id' => 'Поцко С.С.',
        'operator1_id' => 'Гизатулин А.Р.',
        'operator2_id' => 'Юркевич В.В.',
        'operator3_id' => null,
        'operator_helper' => null,
        'tkn' => '12345a',
        'work_plan' => 100.00,
        'work_start' => '2025-01-01 08:00:00',
        'work_finish' => '2025-01-01 16:00:00',
        'work_fact' => 95.00,
        'work_productive' => 8.00,
        'customer' => 'Тестовый заказчик',
        'print_title' => 'Тестовый заказ',
        'circulation' => 500.00,
        'mat1_id' => 'BOPP',
        'mat1count_plan' => 100.00,
        'mat1count' => 95.00,
        'width1' => 210.00,
        'thickness1' => 10.00,
        'colors' => 4,
        'workout_mass' => 10.00,
        'workout_length' => 200.00,
        'workout_m2' => 400.00,
        'otk_mass' => 5.00,
        'waste_plan' => 10.00,
        'waste_print' => 5.00,
        'waste_raw' => 5.00,
        'waste_sum' => 15.00,
        'prepare_mass' => 10.00,
        'prepare_hours' => 2.00,
        'correction_PN' => null,
        'correction_CMYK' => null,
        'electro' => null,
        'mechanical' => null,
        'tech_service' => null,
        'form_glue' => null,
        'service_replacement' => null,
        'speed' => null,
        'no_human' => null,
        'no_work' => null,
        'no_raw' => null,
        'short_downtime' => null,
        'total_downtime' => null,
        'no_reason_downtime' => null,
        'diff_circulation' => 1221,
        'notes' => 'Тестовая нормальная смена',
        'is_idle' => 0,
        'is_complete' => 1,
    ]);

    $printing = Printing::find($request->id);

    expect($printing->id)->toBeInt()
        ->and($printing->machine_id)->toBeString()
        ->and($printing->work_shift)->toBeInt()
        ->and($printing->work_date)->toBeString()
        ->and($printing->master_id)->toBeString()
        ->and($printing->operator1_id)->toBeString()
        ->and($printing->operator2_id)->toBeString()
        ->and($printing->operator3_id)->toBeEmpty()
        ->and($printing->operator_helper)->toBeEmpty()
        ->and($printing->tkn)->toBeString()
        ->and($printing->work_plan)->toBeFloat()
        ->and($printing->work_fact)->toBeFloat()
        ->and($printing->work_productive)->toBeFloat()
        ->and($printing->customer)->toBeString()
        ->and($printing->print_title)->toBeString()
        ->and($printing->circulation)->toBeFloat()
        ->and($printing->mat1_id)->toBeString()
        ->and($printing->mat1count_plan)->toBeFloat()
        ->and($printing->mat1count)->toBeFloat()
        ->and($printing->width1)->toBeFloat()
        ->and($printing->thickness1)->toBeFloat()
        ->and($printing->colors)->toBeInt()
        ->and($printing->workout_mass)->toBeFloat()
        ->and($printing->workout_length)->toBeFloat()
        ->and($printing->workout_m2)->toBeFloat()
        ->and($printing->otk_mass)->toBeFloat()
        ->and($printing->waste_plan)->toBeFloat()
        ->and($printing->waste_print)->toBeFloat()
        ->and($printing->waste_raw)->toBeFloat()
        ->and($printing->waste_sum)->toBeFloat()
        ->and($printing->prepare_mass)->toBeFloat()
        ->and($printing->prepare_hours)->toBeFloat()
        ->and($printing->correction_PN)->toBeEmpty()
        ->and($printing->correction_CMYK)->toBeEmpty()
        ->and($printing->electro)->toBeEmpty()
        ->and($printing->mechanical)->toBeEmpty()
        ->and($printing->tech_service)->toBeEmpty()
        ->and($printing->clean_machine)->toBeEmpty()
        ->and($printing->form_glue)->toBeEmpty()
        ->and($printing->service_replacement)->toBeEmpty()
        ->and($printing->speed)->toBeEmpty()
        ->and($printing->no_human)->toBeEmpty()
        ->and($printing->no_work)->toBeEmpty()
        ->and($printing->no_raw)->toBeEmpty()
        ->and($printing->short_downtime)->toBeEmpty()
        ->and($printing->total_downtime)->toBeEmpty()
        ->and($printing->no_reason_downtime)->toBeEmpty()
        ->and($printing->diff_circulation)->toBeFloat()
        ->and($printing->notes)->toBeString()
        ->and($printing->is_idle)->toBeInt()
        ->and($printing->is_complete)->toBeInt();
})->group('machinesTaskTests');
