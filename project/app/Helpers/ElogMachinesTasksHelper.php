<?php

namespace App\Helpers;

use App\Models\MachinesType;
use App\Models\Material;
use App\Models\Worker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ElogMachinesTasksHelper
{

    public function __construct(public MachinesType $mtype)
    {
    }

    /** Формируем массив данных для краткого отчета по машине
     * @param $class
     * @param $tasks
     * @return array
     */
    public function getReportData ($class, $tasks): array
    {
        $fields_sum = $this->calcSumFieldsArray($class::FIELDS_FOR_REPORT['FIELDS_FOR_REPORT_SUM'], $tasks);
        $fields_res_sum = $this->calcSumFieldsArray($class::FIELDS_FOR_REPORT['IDLE_RES_FIELDS'], $tasks);
        $fields_avg = $this->calcAvgFieldsArray($class::FIELDS_FOR_REPORT['FIELDS_FOR_REPORT_AVG'], $tasks);
        $idles_sum = $this->calcSumFieldsArray($class::FIELDS_FOR_REPORT['ALL_IDLE_FIELDS'], $tasks);
        $idles = ['idles' => array_sum($idles_sum)];
        $uniqueTknCount = ['uniqueTknCount' => $this->countUniqueTkn($tasks) ?? 0];


        return array_merge($fields_sum, $fields_avg, $fields_res_sum,$idles_sum, $idles, $uniqueTknCount);
    }

    /** Кэшируем данные по всем наименованиям машин по типам из MachinesTypes
     * @param string $machineType
     * @return array|Collection
     */
    public function getAllMachinesTitles(string $machineType): array|Collection
    {
        return Cache::rememberForever("$machineType-allTitles", function () use ($machineType) {
            return $this->mtype->getMachines($machineType)
                ->orderBy('queue_order')
                ->pluck('title')
                ->toArray();
        });
    }

    /** Кэшируем данные по актуальным, не заблокированным наименованиям машин по типам из MachinesTypes
     * @param string $machineType
     * @return array|Collection
     */
    public function getCurrentMachinesTitles(string $machineType): array|Collection
    {
        return Cache::rememberForever("$machineType-currentTitles", function () use ($machineType) {
            return $this->mtype->getMachines($machineType)
                ->where('is_blocked', false)
                ->orderBy('queue_order')
                ->pluck('title')
                ->toArray();
        });
    }


    /** Кэшируемый список всех, в т.ч. заблокированных работников завода (Workers)
     * @return array|Collection
     */
    public function getAllWorkersNames(): array|Collection
    {
        return Cache::rememberForever("allWorkersGroup", function () {
            return Worker::pluck('name');
        });
    }


    /** Кэшируемый список актуальных работников завода (Workers)
     * @return array|Collection
     */
    public function getCurrentWorkersNames(): array|Collection
    {
        return Cache::rememberForever("currentWorkersGroup", function () {
            return Worker::where('is_blocked', false)->pluck('name')->all();
        });
    }

    /** Кэшируем данные по всем наименованиям материалов из Materials
     * @param string $materialType
     * @return array|Collection
     */
    public function getMaterialsNames(string $materialType): array|Collection
    {
        return Cache::rememberForever("$materialType-names", function () use ($materialType) {
            return Material::where('type', $materialType)->where('is_blocked', false)->pluck('name')->toArray();
        });
    }

    public function countUniqueTkn(Collection $tasks): int
    {
        return $tasks->whereNotNull('tkn')->unique('tkn')->count();
    }

    private function calcSumFieldsArray(array $fields, $tasks): array
    {
        return collect($fields)->mapWithKeys(fn ($field) => [$field => $tasks->sum($field)])->all();
}

    private function calcAvgFieldsArray(array $fields, $tasks): array
    {
        return collect($fields)->mapWithKeys(fn ($field) => [$field => $tasks->avg($field)])->all();
}
}
