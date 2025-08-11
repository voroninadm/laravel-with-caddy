<?php

namespace App\Models\Elog;

use App\Casts\CuttingProductCast;
use App\Casts\DatepickerDateFormatCast;
use App\Casts\MachineNameToIdCast;
use App\Casts\MaterialNameToIdCast;
use App\Casts\WorkerNameToIdCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cutting extends Model
{
    use HasFactory;

    public $table = 'cuttings';
    protected $guarded = [];

    protected $casts = [
        'machine_id' => MachineNameToIdCast::class,
        'work_date' => DatepickerDateFormatCast::class,
        'work_start' => DatepickerDateFormatCast::class,
        'work_finish' => DatepickerDateFormatCast::class,
        'master_id' => WorkerNameToIdCast::class,
        'operator_id' => WorkerNameToIdCast::class,
        'packer_id' => WorkerNameToIdCast::class,
        'student_id' => WorkerNameToIdCast::class,
        'mat1_id' => MaterialNameToIdCast::class,
        'mat2_id' => MaterialNameToIdCast::class,
        'mat3_id' => MaterialNameToIdCast::class,
        'product_type' => CuttingProductCast::class
    ];

    public const array FIELDS_FOR_REPORT = [
        'ALL_IDLE_FIELDS' => [
            'electro', 'mechanical', 'tech_service', 'knifes_barbell', 'reconfig', 'no_human', 'no_work', 'no_raw'
        ],
        'IDLE_RES_FIELDS' => [],
        'FIELDS_FOR_REPORT_SUM' => [
            'workout_mass', 'workout_length', 'workout_m2', 'otk_mass', 'raw_meters', 'waste_print', 'waste_lam',
            'waste_edge', 'waste_sum', 'electro', 'mechanical', 'tech_service', 'knifes_barbell', 'reconfig',
            'no_human', 'no_work', 'no_raw',
        ],
        'FIELDS_FOR_CIRCULATION' => ['workout_mass', 'otk_mass'],
        'FIELDS_FOR_REPORT_AVG' => ['speed']
    ];
}
