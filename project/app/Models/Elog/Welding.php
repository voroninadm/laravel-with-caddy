<?php

namespace App\Models\Elog;

use App\Casts\DatepickerDateFormatCast;
use App\Casts\MachineNameToIdCast;
use App\Casts\MaterialNameToIdCast;
use App\Casts\WeldingBottomCast;
use App\Casts\WeldingBoxCast;
use App\Casts\WeldingProductCast;
use App\Casts\WorkerNameToIdCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Welding extends Model
{
    use HasFactory;

    public $table = 'weldings';
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
        'operator4_id' => WorkerNameToIdCast::class,
        'operator5_id' => WorkerNameToIdCast::class,
        'student1_id' => WorkerNameToIdCast::class,
        'student2_id' => WorkerNameToIdCast::class,
        'acceptor_id' => WorkerNameToIdCast::class,
        'mat1_id' => MaterialNameToIdCast::class,
        'mat2_id' => MaterialNameToIdCast::class,
        'mat3_id' => MaterialNameToIdCast::class,
        'box_size_id' => WeldingBoxCast::class,
        'bottom_type' => WeldingBottomCast::class,
        'product_type' => WeldingProductCast::class

    ];

    public const array FIELDS_FOR_REPORT = [
        'ALL_IDLE_FIELDS' => [
            'electro', 'mechanical', 'reconfig', 'calibrating', 'change_teflon', 'tech_service', 'no_human',
            'no_work', 'no_raw',
        ],
        'IDLE_RES_FIELDS' => [],
        'FIELDS_FOR_REPORT_SUM' => [
            'workout_count', 'workout_otk', 'count', 'waste_print', 'waste_edge', 'waste_welding', 'waste_sum',
            'electro', 'mechanical', 'reconfig', 'calibrating', 'change_teflon', 'tech_service', 'no_human',
            'no_work', 'no_raw',
        ],
        'FIELDS_FOR_REPORT_AVG' => [
            'product_width', 'length', 'speed'
        ],
        'FIELDS_FOR_CIRCULATION' => ['workout_count', 'workout_otk']
    ];
}
