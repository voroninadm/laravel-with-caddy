<?php

use App\Models\Elog\Welding;

    it('can make new Welding tasks', function () {
        $request = Welding::create([
            'machine_id' => 'Интермат-10',
            'work_shift' => 1,
            'work_date' => '2025-01-01',
            'master_id' => 'Поцко С.С.',
            'operator1_id' => 'Гизатулин А.Р.',
            'operator2_id' => 'Юркевич В.В.',
            'operator3_id' => null,
            'operator4_id' => null,
            'operator5_id' => null,
            'student1_id' => null,
            'student2_id' => null,
            'acceptor_id' => null,
            'tkn' => '12345a',
            'work_plan' => 100.00,
            'work_start' => '2025-01-01 08:00:00',
            'work_finish' => '2025-01-01 16:00:00',
            'work_fact' => 95.00,
            'customer' => 'Тестовый заказчик',
            'print_title' => 'Тестовый заказ',
            'circulation' => 500.00,
            'product_type' => 'Мешок полиэтиленовый',
            'mat1_id' => 'BOPP',
            'mat2_id' => null,
            'mat3_id' => null,
            'product_width' => 210.00,
            'thickness1' => 10.00,
            'thickness2' => null,
            'thickness3' => null,
            'bottom' => 10.00,
            'length' => 200.00,
            'count_plan' => 100.00,
            'count' => 95.00,
            'bottom_type' => 'Трапециевидное',
            'is_pocket' => 1,
            'is_handle' => 0,
            'is_edge' => 0,
            'is_perforation' => 0,
            'is_ziplock' => 0,
            'workout_count' => 10.00,
            'workout_otk' => 5.00,
            'waste_plan' => 10.00,
            'waste_print' => 5.00,
            'waste_edge' => 5.00,
            'waste_welding' => 5.00,
            'waste_sum' => 15.00,
            'box_size_id' => 'Гофрокороб большой б/р',
            'electro' => 10.00,
            'mechanical' => 5.00,
            'speed' => 100.00,
            'reconfig' => 5.00,
            'calibrating' => 5.00,
            'change_teflon' => 5.00,
            'tech_service' => 5.00,
            'no_human' => 5.00,
            'no_work' => 5.00,
            'no_raw' => 5.00,
            'diff_circulation' => 1221,
            'prepare_ok' => 1,
            'notes' => 'Тестовая нормальная смена',
            'is_idle' => 0
        ]);

        $welding = Welding::find($request->id);

        expect($welding->id)->toBeInt()
            ->and($welding->machine_id)->toBeString()
            ->and($welding->work_shift)->toBeInt()
            ->and($welding->work_date)->toBeString()
            ->and($welding->master_id)->toBeString()
            ->and($welding->operator1_id)->toBeString()
            ->and($welding->operator2_id)->toBeString()
            ->and($welding->operator3_id)->toBeEmpty()
            ->and($welding->operator4_id)->toBeEmpty()
            ->and($welding->operator5_id)->toBeEmpty()
            ->and($welding->student1_id)->toBeEmpty()
            ->and($welding->student2_id)->toBeEmpty()
            ->and($welding->acceptor_id)->toBeEmpty()
            ->and($welding->tkn)->toBeString()
            ->and($welding->work_plan)->toBeFloat()
            ->and($welding->work_start)->toBeString()
            ->and($welding->work_finish)->toBeString()
            ->and($welding->work_fact)->toBeFloat()
            ->and($welding->customer)->toBeString()
            ->and($welding->print_title)->toBeString()
            ->and($welding->circulation)->toBeFloat()
            ->and($welding->product_type)->toBeString()
            ->and($welding->mat1_id)->toBeString()
            ->and($welding->mat2_id)->toBeEmpty()
            ->and($welding->mat3_id)->toBeEmpty()
            ->and($welding->product_width)->toBeFloat()
            ->and($welding->thickness1)->toBeFloat()
            ->and($welding->thickness2)->toBeEmpty()
            ->and($welding->thickness3)->toBeEmpty()
            ->and($welding->bottom)->toBeFloat()
            ->and($welding->length)->toBeFloat()
            ->and($welding->count_plan)->toBeFloat()
            ->and($welding->count)->toBeFloat()
            ->and($welding->bottom_type)->toBeString()
            ->and($welding->is_pocket)->toBeInt()
            ->and($welding->is_handle)->toBeInt()
            ->and($welding->is_edge)->toBeInt()
            ->and($welding->is_perforation)->toBeInt()
            ->and($welding->is_ziplock)->toBeInt()
            ->and($welding->workout_count)->toBeFloat()
            ->and($welding->workout_otk)->toBeFloat()
            ->and($welding->waste_plan)->toBeFloat()
            ->and($welding->waste_print)->toBeFloat()
            ->and($welding->waste_edge)->toBeFloat()
            ->and($welding->waste_welding)->toBeFloat()
            ->and($welding->waste_sum)->toBeFloat()
            ->and($welding->box_size_id)->toBeString()
            ->and($welding->electro)->toBeFloat()
            ->and($welding->mechanical)->toBeFloat()
            ->and($welding->speed)->toBeFloat()
            ->and($welding->reconfig)->toBeFloat()
            ->and($welding->calibrating)->toBeFloat()
            ->and($welding->change_teflon)->toBeFloat()
            ->and($welding->tech_service)->toBeFloat()
            ->and($welding->no_human)->toBeFloat()
            ->and($welding->no_work)->toBeFloat()
            ->and($welding->no_raw)->toBeFloat()
            ->and($welding->diff_circulation)->toBeFloat()
            ->and($welding->prepare_ok)->toBeInt()
            ->and($welding->notes)->toBeString()
            ->and($welding->is_idle)->toBeInt();

    })->group('machinesTaskTests');
