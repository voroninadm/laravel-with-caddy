<?php

use App\Models\Elog\Extrusion;

it('can make new Extrusion tasks', function () {
    $request = Extrusion::create([
        'machine_id' => 'Бобинорезка-5',
        'work_shift' => 1,
        'work_date' => '2025-01-01',
        'master_id' => 'Поцко С.С.',
        'machinist1_id' => 'Гизатулин А.Р.',
        'machinist2_id' => null,
        'machinist3_id' => null,
        'student_id' => null,
        'tkn' => '12345b',
        'recipe_number' => 100,
        'part_number' => 'Part-123',
        'work_plan' => 100.00,
        'work_start' => '2025-01-01 08:00:00',
        'work_finish' => '2025-01-01 16:00:00',
        'work_fact' => 95.00,
        'customer' => 'Тестовый заказчик',
        'print_title' => 'Тестовый заказ',
        'circulation_kg' => 500.00,
        'circulation_length' => 200.00,
        'product_type' => 'Полотно',
        'width' => 210.00,
        'thickness' => 10.00,
        'activation_pole' => 5.00,
        'edge' => 5.00,
        'streams' => 10.00,
        'roll_mass' => 10.00,
        'roll_length' => 200.00,
        'roll_diameter' => 100.00,
        'workout_mass' => 10.00,
        'workout_otk' => 5.00,
        'workout_length' => 200.00,
        'workout_m2' => 100.00,
        'waste_plan_roll' => 10.00,
        'waste_roll' => 5.00,
        'waste_trans' => 5.00,
        'waste_plan_edge' => 5.00,
        'waste_edge' => 5.00,
        'waste_plan_ingets' => 5.00,
        'waste_ingets' => 5.00,
        'waste_slice' => 5.00,
        'waste_sum' => 15.00,
        'electro' => 10.00,
        'mechanical' => 5.00,
        'electronics' => 5.00,
        'tech_service' => 5.00,
        'change_net' => 5.00,
        'change_raw' => 5.00,
        'change_order' => 5.00,
        'clean_machine' => 5.00,
        'no_human' => 5.00,
        'no_work' => 5.00,
        'no_raw' => 5.00,
        'prepare_hours' => 1.00,
        'prepare_ok' => 1,
        'diff_circulation' => 1221,
        'notes' => 'Тестовая нормальная смена',
        'is_idle' => 0
    ]);

    $extrusion = Extrusion::find($request->id);

    expect($extrusion->id)->toBeInt()
        ->and($extrusion->machine_id)->toBeString()
        ->and($extrusion->work_shift)->toBeInt()
        ->and($extrusion->work_date)->toBeString()
        ->and($extrusion->master_id)->toBeString()
        ->and($extrusion->machinist1_id)->toBeString()
        ->and($extrusion->machinist2_id)->toBeEmpty()
        ->and($extrusion->machinist3_id)->toBeEmpty()
        ->and($extrusion->student_id)->toBeEmpty()
        ->and($extrusion->tkn)->toBeString()
        ->and($extrusion->recipe_number)->toBeInt()
        ->and($extrusion->part_number)->toBeString()
        ->and($extrusion->work_plan)->toBeFloat()
        ->and($extrusion->work_start)->toBeString()
        ->and($extrusion->work_finish)->toBeString()
        ->and($extrusion->work_fact)->toBeFloat()
        ->and($extrusion->customer)->toBeString()
        ->and($extrusion->print_title)->toBeString()
        ->and($extrusion->circulation_kg)->toBeFloat()
        ->and($extrusion->circulation_length)->toBeFloat()
        ->and($extrusion->product_type)->toBeString()
        ->and($extrusion->width)->toBeFloat()
        ->and($extrusion->thickness)->toBeFloat()
        ->and($extrusion->activation_pole)->toBeFloat()
        ->and($extrusion->edge)->toBeFloat()
        ->and($extrusion->streams)->toBeFloat()
        ->and($extrusion->roll_mass)->toBeFloat()
        ->and($extrusion->roll_length)->toBeFloat()
        ->and($extrusion->roll_diameter)->toBeFloat()
        ->and($extrusion->workout_mass)->toBeFloat()
        ->and($extrusion->workout_otk)->toBeFloat()
        ->and($extrusion->workout_length)->toBeFloat()
        ->and($extrusion->workout_m2)->toBeFloat()
        ->and($extrusion->waste_plan_roll)->toBeFloat()
        ->and($extrusion->waste_roll)->toBeFloat()
        ->and($extrusion->waste_trans)->toBeFloat()
        ->and($extrusion->waste_plan_edge)->toBeFloat()
        ->and($extrusion->waste_edge)->toBeFloat()
        ->and($extrusion->waste_plan_ingets)->toBeFloat()
        ->and($extrusion->waste_ingets)->toBeFloat()
        ->and($extrusion->waste_slice)->toBeFloat()
        ->and($extrusion->waste_sum)->toBeFloat()
        ->and($extrusion->electro)->toBeFloat()
        ->and($extrusion->mechanical)->toBeFloat()
        ->and($extrusion->electronics)->toBeFloat()
        ->and($extrusion->tech_service)->toBeFloat()
        ->and($extrusion->change_net)->toBeFloat()
        ->and($extrusion->change_raw)->toBeFloat()
        ->and($extrusion->change_order)->toBeFloat()
        ->and($extrusion->clean_machine)->toBeFloat()
        ->and($extrusion->no_human)->toBeFloat()
        ->and($extrusion->no_work)->toBeFloat()
        ->and($extrusion->no_raw)->toBeFloat()
        ->and($extrusion->prepare_hours)->toBeFloat()
        ->and($extrusion->prepare_ok)->toBeInt()
        ->and($extrusion->diff_circulation)->toBeFloat()
        ->and($extrusion->notes)->toBeString()
        ->and($extrusion->is_idle)->toBeInt();

})->group('machinesTaskTests');
