<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\test\TestCuttingSeeder;
use Database\Seeders\test\TestExtrusionSeeder;
use Database\Seeders\test\TestFlexoformSeeder;
use Database\Seeders\test\TestLaminationSeeder;
use Database\Seeders\test\TestPrintingSeeder;
use Database\Seeders\test\TestRecyclingSeeder;
use Database\Seeders\test\TestWeldingSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MachinesTypeSeeder::class,
            MachinesSeeder::class,
            UserSeeder::class,
            RolesPermissionsSeeder::class,
            WorkersSeeder::class,
            MaterialsSeeder::class,
            PPPUserWorkerSeeder::class,
            SectorsSeeder::class,

            // TODO перенести в тесты before all
            // for testing uncomment machines task seeders
            TestPrintingSeeder::class,
            TestLaminationSeeder::class,
            TestWeldingSeeder::class,
            TestCuttingSeeder::class,
            TestExtrusionSeeder::class,
            TestRecyclingSeeder::class,
            TestFlexoformSeeder::class
        ]);
    }
}
