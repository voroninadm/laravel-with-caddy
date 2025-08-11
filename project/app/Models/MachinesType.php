<?php

namespace App\Models;

use App\Models\Elog\Cutting;
use App\Models\Elog\Extrusion;
use App\Models\Elog\Lamination;
use App\Models\Elog\Printing;
use App\Models\Elog\Recycling;
use App\Models\Elog\Welding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MachinesType extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function machines (): HasMany
    {
        return $this->hasMany(Machine::class, 'type_id', 'workflow_type')->orderBy('queue_order');
    }

    public function getIdByType(string $type): ?int
    {
        $machineType = self::where('name', $type)->first();
        return $machineType ? $machineType->id : null;
    }

    public function getTypeById(int $id): ?string
    {
        $machineType = self::where('id', $id)->first();
        return $machineType ? $machineType->name : null;
    }

    /** Найти все машины по их типу
     * @param $type
     * @return mixed
     */
    public function getMachines($type): mixed
    {
        return $this->where('name', $type)->first()->machines();
    }

    public function matchClass(string $machine_type): ?string
    {
        return match ($machine_type) {
            'printing' => Printing::class,
            'lamination' => Lamination::class,
            'welding' => Welding::class,
            'cutting' => Cutting::class,
            'extrusion' => Extrusion::class,
            'recycling' => Recycling::class,
            default => null,
        };
    }

    /** Простои по всем машинам определенного типа
     * @param $machine_type - например 'printing'
     * @param $date_from - 'Y-m-d'
     * @param $date_to - 'Y-m-d'
     * @return array
     */
    public function getTypeIdles($machine_type, $date_from, $date_to): array
    {
        $type = $this->matchClass($machine_type);
        $idle_fields = $type::FIELDS_FOR_REPORT['ALL_IDLE_FIELDS'] ?? [];
        $idle_res_fields = $type::FIELDS_FOR_REPORT['IDLE_RES_FIELDS'] ?? [];

        $machines = $this->getMachines($machine_type)->pluck('id');
        $data = [];

        foreach ($machines as $machine_id) {
            $machineData = [];
            $result = $type::where('machine_id', $machine_id)
                ->whereBetween('work_date', [$date_from, $date_to])
                ->get();

            $machineData['machine_name'] = Machine::find($machine_id)->title;

            $sum = 0;
            if (!empty($idle_fields)) {
                foreach ($idle_fields as $field) {
                    $machineData[$field] = $result->sum($field);
                    $sum += $result->sum($field);
                }
            }
            $machineData['sum'] = $sum;

            if (!empty($idle_res_fields)) {
                foreach ($idle_res_fields as $field) {
                    $machineData[$field] = $result->sum($field);
                }
            }
            $data[] = $machineData;
        }
        return $data;
    }

    public function getTitleByName($name)
    {
        return $this->where('name', $name)->value('title');
    }

}
