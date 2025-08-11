<?php

namespace App\Console\Commands;

use App\Models\Machine;
use App\Models\Material;
use App\Models\Worker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class CachePrimeCommand extends Command
{
    protected $signature = 'cache:prime';

    protected $description = 'Заполнить кэш начальными данными (машины, материалы, работники)';

    public function handle()
    {
        if (Schema::hasTable('machines')) {
            $machines = Machine::select('id', 'title')->get();
            Cache::forever("allMachinesIdsTitles", $machines->toArray());
            $this->info('✅ Кэш machines заполнен.');
        } else {
            $this->warn('⚠️ Таблица machines не найдена.');
        }

        if (Schema::hasTable('materials')) {
            $materials = Material::select('id', 'name')->get();
            Cache::forever("allMaterialsIdsNames", $materials->toArray());
            $this->info('✅ Кэш materials заполнен.');
        } else {
            $this->warn('⚠️ Таблица materials не найдена.');
        }

        if (Schema::hasTable('workers')) {
            $workers = Worker::select('id', 'name')->get();
            Cache::forever("allWorkersIdsNames", $workers->toArray());
            $this->info('✅ Кэш workers заполнен.');
        } else {
            $this->warn('⚠️ Таблица workers не найдена.');
        }

        return self::SUCCESS;
    }
}
