<?php

namespace App\Services;

use App\Models\Machine;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

final class ElogMachinesFilterService
{
    protected $workersGroup;
    protected $machinesGroup;
    protected $query;
    protected $elogMachineClass;
    protected $table_name;

    public function __construct()
    {
        $this->workersGroup = Cache::get('allWorkersIdsNames');
        $this->machinesGroup = Cache::get('allMachinesIdsTitles');
    }

    public function __invoke(Request $request, $elogMachineClass): Collection
    {
        $this->elogMachineClass = $elogMachineClass;
        $this->table_name = $elogMachineClass->table;

        $this->initializeQuery();

        $this->applyDatesFilter($request->input('start_date'), $request->input('finish_date'));
        $this->applyMachineFilter($request->input('machine'));
        $this->applyWorkerFilter($request->input('worker'));
        $this->applyTknFilter($request->input('tkn'));
        $this->applyNotCompletedFilter($request->input('not_completed'));

        $this->applyNomenclatureFilter($request->input('nomenclature'));
        $this->applyViketMachinesFilter($request->input('is_viket'));
        $this->applyTaskWithOtkFilter($request->input('is_otk'));
        $this->applyPartNumberFilter($request->input('part_number'));

        $this->query
            ->orderBy('machine_id')
            ->orderBy('work_date')
            ->orderBy('work_shift')
            ->orderBy('work_start');

        return $this->query->get();
    }


    private function initializeQuery(): void
    {
        $this->query = $this->elogMachineClass::query();
    }

    private function applyDatesFilter($work_start, $work_finish): void
    {
        if (filled($work_start) && filled($work_finish)) {
            $this->query->whereBetween('work_date', [$work_start, $work_finish]);
        }
    }

    private function applyMachineFilter($machine): void
    {
        if (filled($machine)) {
            $machineId = collect($this->machinesGroup)->firstWhere('title', $machine)['id'] ?? null;
            if ($machineId) {
                $this->query->where('machine_id', $machineId);
            } else {
                $this->query->whereRaw('1 = 0');
            }
        }
    }

    private function applyWorkerFilter($worker): void
    {
        // Оптимизация: предварительно проверить наличие колонок один раз
        $existingColumns = array_filter([
            'master_id', 'operator_id', 'operator1_id', 'operator2_id', 'operator3_id',
            'operator4_id', 'operator5_id', 'machinist_id', 'machinist1_id', 'machinist2_id',
            'acceptor_id', 'operator_helper', 'helper_id', 'packer_id', 'collector_id'
        ], fn($column) => Schema::hasColumn($this->table_name, $column));

        if (filled($worker) && !empty($existingColumns)) {
            $workerId = collect($this->workersGroup)->firstWhere('name', $worker)['id'] ?? null;
            if ($workerId) {
                $this->query->where(function ($query) use ($workerId, $existingColumns) {
                    foreach ($existingColumns as $column) {
                        $query->orWhere($column, $workerId);
                    }
                });
            } else {
                $this->query->whereRaw('1 = 0');
            }
        }
    }

    public function applyTknFilter($tkn): void
    {
        if (filled($tkn)) {
            $this->query->where('tkn', $tkn);
        }
    }

    private function applyNotCompletedFilter($not_completed): void
    {
        if ($not_completed) {
            $this->query->where('is_complete', false);
        }
    }


    private function applyNomenclatureFilter($nomenclature): void
    {
        $nomenclature_columns = ['nomenclature', 'ingots_type'];

        if (filled($nomenclature)) {
            $typeId = Material::where('name', $nomenclature)->value('id');
            if ($typeId) {
                $this->query->where(function () use ($typeId, $nomenclature_columns) {
                    foreach ($nomenclature_columns as $column) {
                        if (Schema::hasColumn($this->table_name, $column)) {
                            $this->query->orWhere($column, $typeId);
                        }
                    }
                });
            } else {
                $this->query->whereRaw('1 = 0');
            }
        }
    }

    private function applyViketMachinesFilter(?bool $is_viket): void
    {
        if ($is_viket) {
            $machineIds = Machine::where('is_viket', true)->pluck('id')->toArray();
            if ($machineIds) {
                $this->query->whereIn('machine_id', $machineIds);
            } else {
                $this->query->whereRaw('1 = 0');
            }
        }
    }

    private function applyTaskWithOtkFilter(?bool $is_otk): void
    {
        $OTK_columns = ['workout_otk', 'otk_mass'];

        if ($is_otk) {
            $this->query->where(function () use ($OTK_columns) {
                foreach ($OTK_columns as $column) {
                    if (Schema::hasColumn($this->table_name, $column)) {
                        $this->query->where($column, '>', 0);
                    }
                }
            });
        }
    }

    private function applyPartNumberFilter(?string $part_number): void
    {
        if (filled($part_number)) {
            $this->query->where('part_number', $part_number);
        }
    }
}
