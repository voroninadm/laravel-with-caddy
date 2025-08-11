<?php

use App\Models\Elog\Recycling;

it('can make new Recycling tasks', function () {
    $request = Recycling::create([
        'machine_id' => 'Ерёма-1',
        'work_date' => '2025-01-01',
        'work_shift' => 1,
        'master_id' => 'Поцко С.С.',
        'machinist_id' => 'Иванова М.Л.',
        'helper_id' => null,
        'work_start' => '2025-01-01 08:00:00',
        'work_finish' => '2025-01-01 16:00:00',
        'work_fact' => 95.00,

        'nomenclature' => 'GPE-M',
        'workout_mass' => 100.00,
        'ingots_type' => 'чистый PE',
        'waste_mass' => 210.00,

        'prepare_hours' => null,
        'electro' => null,
        'mechanical' => null,
        'electronics' => null,
        'tech_service' => null,
        'change_net' => 1.50,
        'tech_clean' => 1.00,
        'clean_machine' => 1.00,
        'change_knifes' => 1.00,
        'change_cutter' => 1.00,

        'no_human' => null,
        'no_work' => null,
        'no_raw' => null,
        'prepare_ok' => 1,
        'notes' => 'Тестовая нормальная смена',
        'is_idle' => 0,
    ]);

    $recycling = Recycling::find($request->id);

    expect($recycling->id)->toBeInt()
        ->and($recycling->machine_id)->toBeString()
        ->and($recycling->work_shift)->toBeInt()
        ->and($recycling->work_date)->toBeString()
        ->and($recycling->master_id)->toBeString()
        ->and($recycling->machinist_id)->toBeString()
        ->and($recycling->helper_id)->toBeEmpty()
        ->and($recycling->work_start)->toBeString()
        ->and($recycling->work_finish)->toBeString()
        ->and($recycling->work_fact)->toBeFloat()
        ->and($recycling->nomenclature)->toBeString()
        ->and($recycling->workout_mass)->toBeFloat()
        ->and($recycling->ingots_type)->toBeString()
        ->and($recycling->waste_mass)->toBeFloat()
        ->and($recycling->prepare_hours)->toBeEmpty()
        ->and($recycling->electro)->toBeEmpty()
        ->and($recycling->mechanical)->toBeEmpty()
        ->and($recycling->electronics)->toBeEmpty()
        ->and($recycling->tech_service)->toBeEmpty()
        ->and($recycling->change_net)->toBeFloat()
        ->and($recycling->tech_clean)->toBeFloat()
        ->and($recycling->clean_machine)->toBeFloat()
        ->and($recycling->change_knifes)->toBeFloat()
        ->and($recycling->change_cutter)->toBeFloat()
        ->and($recycling->no_human)->toBeEmpty()
        ->and($recycling->no_work)->toBeEmpty()
        ->and($recycling->no_raw)->toBeEmpty()
        ->and($recycling->prepare_ok)->toBeInt()
        ->and($recycling->notes)->toBeString()
        ->and($recycling->is_idle)->toBeInt();

})->group('machinesTaskTests');
