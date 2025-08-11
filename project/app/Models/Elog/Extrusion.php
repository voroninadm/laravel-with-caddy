<?php

namespace App\Models\Elog;

use App\Casts\DatepickerDateFormatCast;
use App\Casts\ExtrusionProductCast;
use App\Casts\MachineNameToIdCast;
use App\Casts\WorkerNameToIdCast;
use App\Models\Machine;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extrusion extends Model
{
    use HasFactory;

    public $table = 'extrusions';
    protected $guarded = [];

    protected $casts = [
        'machine_id' => MachineNameToIdCast::class,
        'work_date' => DatepickerDateFormatCast::class,
        'work_start' => DatepickerDateFormatCast::class,
        'work_finish' => DatepickerDateFormatCast::class,
        'master_id' => WorkerNameToIdCast::class,
        'machinist1_id' => WorkerNameToIdCast::class,
        'machinist2_id' => WorkerNameToIdCast::class,
        'machinist3_id' => WorkerNameToIdCast::class,
        'student_id' => WorkerNameToIdCast::class,
        'product_type' => ExtrusionProductCast::class
    ];

    public const array FIELDS_FOR_REPORT = [
        'ALL_IDLE_FIELDS' => [
            'electro', 'mechanical', 'electronics', 'tech_service', 'change_net', 'change_raw', 'change_order',
            'clean_machine', 'no_human', 'no_work', 'no_raw'
        ],
        'IDLE_RES_FIELDS' => [],
        'FIELDS_FOR_REPORT_SUM' => [
            'workout_mass', 'workout_length', 'workout_m2', 'workout_otk', 'waste_roll', 'waste_trans', 'waste_edge',
            'waste_ingets', 'waste_slice', 'waste_sum', 'electro', 'mechanical', 'electronics', 'tech_service',
            'change_net', 'change_raw', 'change_order', 'clean_machine', 'prepare_hours', 'no_human', 'no_work', 'no_raw',
        ],
        'FIELDS_FOR_REPORT_AVG' => ['width', 'thickness'],
        'FIELDS_FOR_CIRCULATION' => ['workout_mass', 'workout_otk']
    ];
}
