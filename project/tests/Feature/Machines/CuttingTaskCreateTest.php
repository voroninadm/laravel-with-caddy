<?php

use App\Models\Elog\Cutting;


it('can make new Cutting tasks', function () {
    $request = Cutting::create([
        'machine_id' => 'Бобинорезка-5',
        'work_shift' => 1,
        'work_date' => '2025-01-01',
        'master_id' => 'Поцко С.С.',
        'operator_id' => 'Гизатулин А.Р.',
        'packer_id' => null,
        'student_id' => null,
        'tkn' => '12345a',
        'work_plan' => 100.00,
        'work_start' => '2025-01-01 08:00:00',
        'work_finish' => '2025-01-01 16:00:00',
        'work_fact' => 95.00,
        'customer' => 'Тестовый заказчик',
        'print_title' => 'Тестовый заказ',
        'circulation' => 500.00,
        'product_type' => 'Этикетно-упаковочная продукция',
        'mat1_id' => 'BOPP',
        'mat2_id' => null,
        'mat3_id' => null,
        'thickness1' => 10.00,
        'thickness2' => null,
        'thickness3' => null,
        'canvas_width' => 210.00,
        'count_plan' => 100.00,
        'count' => 95.00,
        'streams' => 10.00,
        'stream_width' => 20.10,
        'workout_mass' => 10.00,
        'otk_mass' => 5.00,
        'workout_length' => 200.00,
        'workout_m2' => 100.00,
        'raw_meters' => 50.00,
        'waste_plan' => 10.00,
        'waste_print' => 5.00,
        'waste_lam' => 5.00,
        'waste_edge' => 5.00,
        'waste_sum' => 15.00,
        'electro' => 10.00,
        'mechanical' => 5.00,
        'speed' => 100.00,
        'tech_service' => 5.00,
        'knifes_barbell' => 5.00,
        'reconfig' => 5.00,
        'no_human' => 5.00,
        'no_work' => 5.00,
        'no_raw' => 5.00,
        'prepare_ok' => 1,
        'diff_circulation' => 1221,
        'notes' => 'Тестовая нормальная смена',
        'is_idle' => 0
    ]);

    $cutting = Cutting::find($request->id);

    expect($cutting->id)->toBeInt()
        ->and($cutting->machine_id)->toBeString()
        ->and($cutting->work_shift)->toBeInt()
        ->and($cutting->work_date)->toBeString()
        ->and($cutting->master_id)->toBeString()
        ->and($cutting->operator_id)->toBeString()
        ->and($cutting->packer_id)->toBeEmpty()
        ->and($cutting->student_id)->toBeEmpty()
        ->and($cutting->tkn)->toBeString()
        ->and($cutting->work_plan)->toBeFloat()
        ->and($cutting->work_start)->toBeString()
        ->and($cutting->work_finish)->toBeString()
        ->and($cutting->work_fact)->toBeFloat()
        ->and($cutting->customer)->toBeString()
        ->and($cutting->print_title)->toBeString()
        ->and($cutting->circulation)->toBeFloat()
        ->and($cutting->product_type)->toBeString()
        ->and($cutting->mat1_id)->toBeString()
        ->and($cutting->mat2_id)->toBeEmpty()
        ->and($cutting->mat3_id)->toBeEmpty()
        ->and($cutting->thickness1)->toBeFloat()
        ->and($cutting->thickness2)->toBeEmpty()
        ->and($cutting->thickness3)->toBeEmpty()
        ->and($cutting->canvas_width)->toBeFloat()
        ->and($cutting->count_plan)->toBeFloat()
        ->and($cutting->count)->toBeFloat()
        ->and($cutting->streams)->toBeInt()
        ->and($cutting->stream_width)->toBeInt()
        ->and($cutting->workout_mass)->toBeFloat()
        ->and($cutting->otk_mass)->toBeFloat()
        ->and($cutting->workout_length)->toBeFloat()
        ->and($cutting->workout_m2)->toBeFloat()
        ->and($cutting->raw_meters)->toBeFloat()
        ->and($cutting->waste_plan)->toBeFloat()
        ->and($cutting->waste_print)->toBeFloat()
        ->and($cutting->waste_lam)->toBeFloat()
        ->and($cutting->waste_edge)->toBeFloat()
        ->and($cutting->waste_sum)->toBeFloat()
        ->and($cutting->electro)->toBeFloat()
        ->and($cutting->mechanical)->toBeFloat()
        ->and($cutting->speed)->toBeFloat()
        ->and($cutting->tech_service)->toBeFloat()
        ->and($cutting->knifes_barbell)->toBeFloat()
        ->and($cutting->reconfig)->toBeFloat()
        ->and($cutting->no_human)->toBeFloat()
        ->and($cutting->no_work)->toBeFloat()
        ->and($cutting->no_raw)->toBeFloat()
        ->and($cutting->prepare_ok)->toBeInt()
        ->and($cutting->diff_circulation)->toBeFloat()
        ->and($cutting->notes)->toBeString()
        ->and($cutting->is_idle)->toBeInt();

})->group('machinesTaskTests');
