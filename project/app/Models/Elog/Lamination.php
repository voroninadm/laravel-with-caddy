<?php

namespace App\Models\Elog;

use App\Casts\DatepickerDateFormatCast;
use App\Casts\MachineNameToIdCast;
use App\Casts\MaterialNameToIdCast;
use App\Casts\WorkerNameToIdCast;
use App\Models\Machine;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lamination extends Model
{
    use HasFactory;

    public $table = 'laminations';
    protected $guarded = [];

    protected $casts = [
        'machine_id' => MachineNameToIdCast::class,
        'work_date' => DatepickerDateFormatCast::class,
        'work_start' => DatepickerDateFormatCast::class,
        'work_finish' => DatepickerDateFormatCast::class,
        'master_id' => WorkerNameToIdCast::class,
        'operator1_id' => WorkerNameToIdCast::class,
        'operator2_id' => WorkerNameToIdCast::class,
        'operator3_id' => WorkerNameToIdCast::class,
        'student_id' => WorkerNameToIdCast::class,
        'helper_id' => WorkerNameToIdCast::class,
        'mat1_id' => MaterialNameToIdCast::class,
        'mat2_id' => MaterialNameToIdCast::class,
        'covering_id' => MaterialNameToIdCast::class,
    ];

    public const array FIELDS_FOR_REPORT = [
        'ALL_IDLE_FIELDS' => ['prepare_hours', 'tech_clean', 'change_glue', 'electro',
            'mechanical', 'tech_service', 'no_human', 'no_work', 'no_raw', 'no_reason_downtime', 'short_downtime'
        ],
        'IDLE_RES_FIELDS' => [ 'work_productive', 'total_downtime'],
        'FIELDS_FOR_REPORT_SUM' => [
            'mat1count', 'mat2count', 'covering_count', 'workout_mass', 'workout_length', 'workout_m2', 'otk_mass',
            'waste_print', 'waste_lam', 'waste_sum', 'prepare_hours', 'tech_clean',
            'change_glue', 'electro', 'mechanical', 'tech_service', 'no_human', 'no_work', 'no_raw',
        ],
        'FIELDS_FOR_REPORT_AVG' => [
            'width1', 'thickness1', 'width2', 'thickness2', 'covering_width', 'covering_thickness',
        ],
        'FIELDS_FOR_CIRCULATION' => ['workout_mass', 'otk_mass']
    ];
}
