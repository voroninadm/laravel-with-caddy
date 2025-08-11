<?php

namespace App\Models\Elog;

use App\Casts\DatepickerDateFormatCast;
use App\Casts\MachineNameToIdCast;
use App\Casts\WorkerNameToIdCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flexoform extends Model
{
    use HasFactory;

    public $table = 'flexoforms';
    protected $guarded = [];

    protected $casts = [
        'machine_id' => MachineNameToIdCast::class,
        'print_machine_id' => MachineNameToIdCast::class,
        'work_date' => DatepickerDateFormatCast::class,
        'work_start' => DatepickerDateFormatCast::class,
        'work_finish' => DatepickerDateFormatCast::class,
        'master_id' => WorkerNameToIdCast::class,
        'collector_id' => WorkerNameToIdCast::class,
        'paints_and_sticky' => 'json',

    ];
    public const array FIELDS_FOR_REPORT = [
        'ALL_IDLE_FIELDS' => [],
        'IDLE_RES_FIELDS' => [],
        'FIELDS_FOR_REPORT_SUM' => [],
        'FIELDS_FOR_REPORT_AVG' => [],
        'FIELDS_FOR_CIRCULATION' => []
    ];
}
