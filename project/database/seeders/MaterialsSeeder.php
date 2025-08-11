<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            ['name' => 'BOPP', 'type' => 'material', 'is_blocked' => true],
            ['name' => 'BOPP:белый', 'type' => 'material', 'is_blocked' => false],
            ['name' => 'BOPP: жемчужный', 'type' => 'material', 'is_blocked' => false],
            ['name' => 'BOPP: матовый', 'type' => 'material', 'is_blocked' => false],
            ['name' => 'BOPP: металлизированный', 'type' => 'material', 'is_blocked' => false],
            ['name' => 'BOPP: прозрачный', 'type' => 'material', 'is_blocked' => false],
            ['name' => 'CPP', 'type' => 'material', 'is_blocked' => false],
            ['name' => 'CPP: ламинация', 'type' => 'material', 'is_blocked' => false],
            ['name' => 'PE', 'type' => 'material', 'is_blocked' => true],
            ['name' => 'PE: белый', 'type' => 'material', 'is_blocked' => false],
            ['name' => 'PE: прозрачный', 'type' => 'material', 'is_blocked' => false],
            ['name' => 'PE: цветной', 'type' => 'material', 'is_blocked' => false],
            ['name' => 'PE: черно-белый', 'type' => 'material', 'is_blocked' => false],
            ['name' => 'PET', 'type' => 'material', 'is_blocked' => false],
            ['name' => 'Фольга', 'type' => 'material', 'is_blocked' => false],

            ['name' => 'Матовый лак', 'type' => 'covering', 'is_blocked' => false],
            ['name' => 'Релиз лак', 'type' => 'covering', 'is_blocked' => false],
            ['name' => 'Холодный клей', 'type' => 'covering', 'is_blocked' => false],
            ['name' => 'Краска', 'type' => 'covering', 'is_blocked' => false],
            ['name' => 'Сольвентный клей', 'type' => 'covering', 'is_blocked' => false],
            ['name' => 'Бессольвентный клей', 'type' => 'covering', 'is_blocked' => false],
            ['name' => 'Тактильный лак', 'type' => 'covering', 'is_blocked' => false],

            ['name' => 'BOPP + PET', 'type' => 'material', 'is_blocked' => false],
            ['name' => 'PET + PET', 'type' => 'material', 'is_blocked' => false],
            ['name' => 'PET + AL', 'type' => 'material', 'is_blocked' => false],
            ['name' => 'PET + BOPP', 'type' => 'material', 'is_blocked' => false],

            ['name' => 'GPE-1 ч/б', 'type' => 'granulate', 'is_blocked' => false],
            ['name' => 'GPE-1 белый', 'type' => 'granulate', 'is_blocked' => false],
            ['name' => 'GPE-1 прозрачный', 'type' => 'granulate', 'is_blocked' => false],
            ['name' => 'GPE-2', 'type' => 'granulate', 'is_blocked' => false],
            ['name' => 'GPE-M', 'type' => 'granulate', 'is_blocked' => false],
            ['name' => 'GPP-M', 'type' => 'granulate', 'is_blocked' => false],
            ['name' => 'G-R9', 'type' => 'granulate', 'is_blocked' => false],
            ['name' => 'GPP#1', 'type' => 'granulate', 'is_blocked' => false],
            ['name' => 'GPP#2', 'type' => 'granulate', 'is_blocked' => false],
            ['name' => 'GLam', 'type' => 'granulate', 'is_blocked' => false],

            ['name' => 'Чистый PE', 'type' => 'ingot', 'is_blocked' => false],
            ['name' => 'Цветной PE', 'type' => 'ingot', 'is_blocked' => false],
            ['name' => 'Чистый CPP', 'type' => 'ingot', 'is_blocked' => false],
            ['name' => 'Цветной CPP', 'type' => 'ingot', 'is_blocked' => false],
        ];

        Material::factory()->createMany($materials);
    }
}
