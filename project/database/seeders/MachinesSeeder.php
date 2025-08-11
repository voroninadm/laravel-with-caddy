<?php

namespace Database\Seeders;

use App\Models\Machine;
use Illuminate\Database\Seeder;

class MachinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void

    {
        $machineTypesData = [
            ['name' => 'Allstein', 'title' => 'Allstein', 'type_id' => 1, 'encost_id' => 13, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 1],
            ['name' => 'Fisher4', 'title' => 'Фишер-Крекке-4', 'type_id' => 1, 'encost_id' => 4, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 4],
            ['name' => 'Fisher5', 'title' => 'Фишер-Крекке-5', 'type_id' => 1, 'encost_id' => 29, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 5],
            ['name' => 'Fisher6', 'title' => 'Фишер-Крекке-6', 'type_id' => 1, 'encost_id' => 3, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 6],
            ['name' => 'Miraflex-1', 'title' => 'Мирафлекс 1', 'type_id' => 1, 'encost_id' => 2, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 2],
            ['name' => 'Miraflex-2', 'title' => 'Мирафлекс 2', 'type_id' => 1, 'encost_id' => 30, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 3],

            ['name' => 'Laminator-1', 'title' => 'Ламинатор 1', 'type_id' => 2, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 1],
            ['name' => 'Laminator-2', 'title' => 'Ламинатор 2', 'type_id' => 2, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 2],
            ['name' => 'Laminator-3', 'title' => 'Ламинатор 3', 'type_id' => 2, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 3],

            ['name' => 'Hudson-Sharp-2', 'title' => 'Хадсон-шарп-2', 'type_id' => 3, 'encost_id' => null, 'is_viket' => 1, 'is_blocked' => true, 'queue_order' => 1],
            ['name' => 'Hudson-Sharp-3', 'title' => 'Хадсон-шарп-3', 'type_id' => 3, 'encost_id' => null, 'is_viket' => 1, 'is_blocked' => true, 'queue_order' => 2],
            ['name' => 'Hudson-Sharp-4', 'title' => 'Хадсон-шарп-4', 'type_id' => 3, 'encost_id' => null, 'is_viket' => 1, 'is_blocked' => false, 'queue_order' => 3],
            ['name' => 'Intermat-3', 'title' => 'Интермат-3', 'type_id' => 3, 'encost_id' => null, 'is_viket' => 1, 'is_blocked' => false, 'queue_order' => 4],
            ['name' => 'Intermat-4', 'title' => 'Интермат-4', 'type_id' => 3, 'encost_id' => null, 'is_viket' => 1, 'is_blocked' => false, 'queue_order' => 5],
            ['name' => 'Intermat-5', 'title' => 'Интермат-5', 'type_id' => 3, 'encost_id' => null, 'is_viket' => 1, 'is_blocked' => false, 'queue_order' => 6],
            ['name' => 'Intermat-6', 'title' => 'Интермат-6', 'type_id' => 3, 'encost_id' => null, 'is_viket' => 1, 'is_blocked' => false, 'queue_order' => 7],
            ['name' => 'Intermat-7', 'title' => 'Интермат-7', 'type_id' => 3, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 8],
            ['name' => 'Intermat-8', 'title' => 'Интермат-8', 'type_id' => 3, 'encost_id' => null, 'is_viket' => 1, 'is_blocked' => false, 'queue_order' => 9],
            ['name' => 'Intermat-9', 'title' => 'Интермат-9', 'type_id' => 3, 'encost_id' => null, 'is_viket' => 1, 'is_blocked' => false, 'queue_order' => 10],
            ['name' => 'Intermat-10', 'title' => 'Интермат-10', 'type_id' => 3, 'encost_id' => null, 'is_viket' => 1, 'is_blocked' => false, 'queue_order' => 11],
            ['name' => 'Intermat-11', 'title' => 'Интермат-11', 'type_id' => 3, 'encost_id' => null, 'is_viket' => 1, 'is_blocked' => false, 'queue_order' => 12],
            ['name' => 'Intermat-12', 'title' => 'Интермат-12', 'type_id' => 3, 'encost_id' => null, 'is_viket' => 1, 'is_blocked' => false, 'queue_order' => 13],
            ['name' => 'Intermat-13', 'title' => 'Интермат-13', 'type_id' => 3, 'encost_id' => null, 'is_viket' => 1, 'is_blocked' => false, 'queue_order' => 14],
            ['name' => 'Intermat-14', 'title' => 'Интермат-14', 'type_id' => 3, 'encost_id' => null, 'is_viket' => 1, 'is_blocked' => false, 'queue_order' => 15],
            ['name' => 'Elba', 'title' => 'Эльба', 'type_id' => 3, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 14],
            ['name' => 'Heima-2', 'title' => 'Хейма-2', 'type_id' => 3, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 15],
            ['name' => 'Totani', 'title' => 'Тотани', 'type_id' => 3, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 16],
            ['name' => 'Waterline', 'title' => 'Вотерлайн', 'type_id' => 3, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 17],

            ['name' => 'Bobcut-1', 'title' => 'Бобинорезка-3', 'type_id' => 4, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 1],
            ['name' => 'Bobcut-2', 'title' => 'Бобинорезка-4', 'type_id' => 4, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 2],
            ['name' => 'Bobcut-3', 'title' => 'Бобинорезка-5', 'type_id' => 4, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 3],
            ['name' => 'Bobcut-3', 'title' => 'Бобинорезка-6', 'type_id' => 4, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 4],
            ['name' => 'Rewinder', 'title' => 'Перемотчик', 'type_id' => 4, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 5],

            ['name' => 'Kast', 'title' => 'КАСТ', 'type_id' => 5, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 4],
            ['name' => 'Extruder-20', 'title' => 'Экструдер-20', 'type_id' => 5, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 1],
            ['name' => 'Extruder-21', 'title' => 'Экструдер-21', 'type_id' => 5, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 2],
            ['name' => 'Extruder-22', 'title' => 'Экструдер-22', 'type_id' => 5, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 3],

            ['name' => 'Erema-1', 'title' => 'Ерёма-1', 'type_id' => 6, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 1],
            ['name' => 'Erema-2', 'title' => 'Ерёма-2', 'type_id' => 6, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 2],

            ['name' => 'Microflex MS', 'title' => 'Microflex MS', 'type_id' => 7, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 1],
            ['name' => 'Microflex EE', 'title' => 'Microflex EE', 'type_id' => 7, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 2],
            ['name' => 'Smart GPS', 'title' => 'Smart GPS', 'type_id' => 7, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 3],
            ['name' => 'JM Heaford', 'title' => 'JM Heaford', 'type_id' => 7, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 4],
            ['name' => 'Allstein', 'title' => 'Allstein', 'type_id' => 7, 'encost_id' => null, 'is_viket' => null, 'is_blocked' => false, 'queue_order' => 5],

        ];

        Machine::factory()->createMany($machineTypesData);
    }
}
