<?php

namespace Database\Seeders;

use App\Models\MachinesType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MachinesTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $machineTypesData = [
            ['name' => 'printing', 'title' => 'Печать', 'encost_type' => 3, 'workflow_type' => 1],
            ['name' => 'lamination', 'title' => 'Ламинация', 'encost_type' => null, 'workflow_type' => 2],
            ['name' => 'welding', 'title' => 'Сварка', 'encost_type' => null, 'workflow_type' => 3],
            ['name' => 'cutting', 'title' => 'Резка', 'encost_type' => null, 'workflow_type' => 4],
            ['name' => 'extrusion', 'title' => 'Экструзия', 'encost_type' => null, 'workflow_type' => 5],
            ['name' => 'recycling', 'title' => 'Переработка', 'encost_type' => null, 'workflow_type' => 6],
            ['name' => 'spoolcutting', 'title' => 'Шпулерезка', 'encost_type' => null, 'workflow_type' => 4],
            ['name' => 'flexoform', 'title' => 'Монтаж флексоформ', 'encost_type' => null, 'workflow_type' => 7],
        ];

        MachinesType::factory()->createMany($machineTypesData);
    }
}
