<?php

namespace App\Models\Elog;

use App\Casts\DatepickerDateFormatCast;
use App\Casts\MachineNameToIdCast;
use App\Casts\MaterialNameToIdCast;
use App\Casts\WorkerNameToIdCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printing extends Model
{
    use HasFactory;

    public $table = 'printings';
    protected $guarded = false;

    protected $casts = [
        'machine_id' => MachineNameToIdCast::class,
        'work_date' => DatepickerDateFormatCast::class,
        'work_start' => DatepickerDateFormatCast::class,
        'work_finish' => DatepickerDateFormatCast::class,
        'master_id' => WorkerNameToIdCast::class,
        'operator1_id' => WorkerNameToIdCast::class,
        'operator2_id' => WorkerNameToIdCast::class,
        'operator3_id' => WorkerNameToIdCast::class,
        'operator_helper' => WorkerNameToIdCast::class,
        'mat1_id' => MaterialNameToIdCast::class,
    ];

    public const array FIELDS_FOR_REPORT = [
        'ALL_IDLE_FIELDS' => [
            'prepare_hours', 'correction_PN', 'correction_CMYK', 'electro', 'mechanical', 'service_replacement', 'form_glue',
            'tech_service', 'no_reason_downtime', 'no_human', 'no_work', 'no_raw', 'short_downtime'
        ],
        'IDLE_RES_FIELDS' => [
            'work_productive', 'total_downtime'
        ],
        'FIELDS_FOR_REPORT_SUM' => [
            'mat1count', 'workout_mass', 'workout_length', 'workout_m2', 'otk_mass', 'waste_print', 'waste_raw',
            'waste_sum', 'prepare_mass'
        ],
        'FIELDS_FOR_REPORT_AVG' => [
            'width1', 'thickness1', 'colors', 'speed'
        ],
        'FIELDS_FOR_CIRCULATION' => [
            'workout_mass', 'otk_mass'
        ]
    ];
}
