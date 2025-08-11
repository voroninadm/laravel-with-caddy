<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectors = [
            ['name' => 'General', 'title' => 'Общее', 'is_virtual' => true],
            ['name' => 'AHO', 'title' => 'АХО', 'is_virtual' => false],
            ['name' => 'ITR', 'title' => 'ИТР', 'is_virtual' => false],
            ['name' => 'OTK', 'title' => 'ОТК', 'is_virtual' => false],
            ['name' => 'PPP', 'title' => 'ППП', 'is_virtual' => false],
            ['name' => 'RES', 'title' => 'РЭС', 'is_virtual' => false],
            ['name' => 'TSU', 'title' => 'ТСУ', 'is_virtual' => false],
            ['name' => 'UP', 'title' => 'Участок печати', 'is_virtual' => false],
            ['name' => 'UPFF', 'title' => 'Формный участок', 'is_virtual' => false],
            ['name' => 'Direction', 'title' => 'Управление', 'is_virtual' => false],
        ];

        Sector::factory()->createMany($sectors);
    }
}
