<?php

namespace App\Services;

use App\Exports\IdlesExport;
use App\Exports\Machines\AllWorksMachineExport;
use App\Exports\Machines\WorkoutMachineExport;
use App\Exports\Machines\WorksReport;
use App\Exports\SpoolcutterWorkoutExport;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelExportService
{
    public function convertAllTasksToExcel($task_type, $dates, $tasks): Response|BinaryFileResponse
    {
        $work_start = Carbon::parse($dates['start_date'])->format('d.m.Y');
        $work_finish = Carbon::parse($dates['finish_date'])->format('d.m.Y');
        return (new AllWorksMachineExport($tasks, $work_start, $work_finish, $task_type))
            ->download('Полный отчет.xlsx');
    }

    public function convertWorksReportTasksToExcel($task_type,$dates, $tasks): Response|BinaryFileResponse
    {
        $work_start = Carbon::parse($dates['start_date'])->format('d.m.Y');
        $work_finish = Carbon::parse($dates['finish_date'])->format('d.m.Y');
        return (new WorksReport($tasks, $work_start, $work_finish, $task_type))
            ->download('Краткий отчет.xlsx');
    }

    public function convertWorkoutTasksToExcel($task_type, $dates, $tasks): Response|BinaryFileResponse
    {
        $work_start = Carbon::parse($dates['start_date'])->format('d.m.Y');
        $work_finish = Carbon::parse($dates['finish_date'])->format('d.m.Y');
        return (new WorkoutMachineExport($tasks, $work_start, $work_finish, $task_type))
            ->download('Отчет по выработке.xlsx');
    }

    public function convertIdlesToExcel($dates, $idlesData): Response|BinaryFileResponse
    {
        $work_start = Carbon::parse($dates['start_date'])->format('d.m.Y');
        $work_finish = Carbon::parse($dates['finish_date'])->format('d.m.Y');
        return (new IdlesExport($idlesData, $work_start, $work_finish))->download('отчет по простоям.xlsx');
    }

    public function convertSpoolcutterOrdersToExcel($dates, $spoolcutterData): Response|BinaryFileResponse
    {
        $work_start = Carbon::parse($dates[0])->format('d.m.Y');
        $work_finish = Carbon::parse($dates[1])->format('d.m.Y');
        $spoolcutter = $spoolcutterData['spoolcutter'];
        $spoolcutter_workout = $spoolcutterData['workout'];

        return (new SpoolcutterWorkoutExport($work_start, $work_finish, $spoolcutter, $spoolcutter_workout))->download('отчет шпулереза.xlsx');
    }
}
