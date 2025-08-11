<?php

namespace Database\Seeders\test;

use App\Models\Elog\Flexoform;
use Illuminate\Database\Seeder;

class TestFlexoformSeeder extends Seeder
{
    public function run(): void

    {
        Flexoform::create([
            'machine_id' => 'Microflex MS',
            'print_machine_id' => 'Allstein',
            'work_date' => '2025-01-01',
            'work_shift' => 1,
            'master_id' => 'Поцко С.С.',
            'collector_id' => 'Иванова М.Л.',
            'tkn' => '12345a',
            'work_start' => '2025-01-01 08:00:00',
            'work_finish' => '2025-01-01 16:00:00',
            'work_fact' => 95.00,
            'customer' => 'Тестовый заказчик',
            'print_title' => 'Тестовый заказ',

            'design_number' => '12345',
            'streams' => 2,
            'sleeves_diameter' => 100.00,
            'sleeves_fact' => 5,
            'paints_count' => 8,
            'paints_and_sticky' => [
                ["paints" => "CMYK", "sticky_name" => "E1220", "sticky_brand" => "3M", "sticky_thickness" => 0.5],
            ],
            'new_forms' => 'все',
            'aniloks' => 5,

            'unwind_direction' => 'flow',
            'is_streams_distance_ok' => 1,
            'is_mounting_dots_ok' => 1,
            'notes' => 'Тестовая нормальная смена',
        ]);
    }
}
