<?php

namespace App\Models\Elog;

use App\Casts\DatepickerDateFormatCast;
use App\Casts\MachineNameToIdCast;
use App\Casts\MaterialNameToIdCast;
use App\Casts\RecyclingIngotsCast;
use App\Casts\WorkerNameToIdCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recycling extends Model
{
    use HasFactory;

    public $table = 'recyclings';
    protected $guarded = [];

    protected $casts = [
        'machine_id' => MachineNameToIdCast::class,
        'work_date' => DatepickerDateFormatCast::class,
        'work_start' => DatepickerDateFormatCast::class,
        'work_finish' => DatepickerDateFormatCast::class,
        'master_id' => WorkerNameToIdCast::class,
        'machinist_id' => WorkerNameToIdCast::class,
        'helper_id' => WorkerNameToIdCast::class,
        'nomenclature' => MaterialNameToIdCast::class,
        'ingots_type' => MaterialNameToIdCast::class,
    ];

    public const array FIELDS_FOR_REPORT = [
        'ALL_IDLE_FIELDS' => [
            'electro', 'mechanical', 'electronics', 'tech_service', 'change_net', 'change_knifes', 'change_cutter',
            'clean_machine', 'tech_clean', 'no_human', 'no_work', 'no_raw'
        ],
        'IDLE_RES_FIELDS' => [],
        'FIELDS_FOR_REPORT_SUM' => [
            'workout_mass', 'waste_mass', 'electro', 'mechanical', 'electronics', 'tech_service', 'change_net', 'tech_clean',
            'clean_machine', 'change_knifes', 'change_cutter', 'prepare_hours', 'no_human', 'no_work', 'no_raw',
        ],
        'FIELDS_FOR_REPORT_AVG' => []
    ];
}
