<?php

namespace Database\Seeders\test;

use App\Models\Elog\Recycling;
use Illuminate\Database\Seeder;

class TestRecyclingSeeder extends Seeder
{
    public function run(): void

    {
        Recycling::create([
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
            'ingots_type' => 1,
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

        Recycling::create([
            'machine_id' => 'Ерёма-2',
            'work_date' => '2025-01-02',
            'work_shift' => 2,
            'master_id' => 'Поцко С.С.',
            'machinist_id' => null,
            'helper_id' => null,
            'work_start' => null,
            'work_finish' => null,
            'work_fact' => null,

            'nomenclature' => null,
            'workout_mass' => null,
            'ingots_type' => null,
            'waste_mass' => null,

            'prepare_hours' => null,
            'electro' => 12.00,
            'mechanical' => null,
            'electronics' => null,
            'tech_service' => null,
            'change_net' => null,
            'tech_clean' => null,
            'clean_machine' => null,
            'change_knifes' => null,
            'change_cutter' => null,

            'no_human' => null,
            'no_work' => null,
            'no_raw' => null,
            'prepare_ok' => null,
            'notes' => 'Тестовая смена 12 часов электрика простой',
            'is_idle' => 1,
        ]);
    }
}
