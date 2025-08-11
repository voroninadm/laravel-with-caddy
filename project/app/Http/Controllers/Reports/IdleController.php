<?php

namespace App\Http\Controllers\Reports;

use App\Helpers\ElogMachinesTasksHelper;
use App\Http\Controllers\Controller;
use App\Models\MachinesType;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class IdleController extends Controller
{

    public function __construct(
        protected MachinesType            $machinesType,
        protected ElogMachinesTasksHelper $tasksService,
        protected ExcelExportService      $excelService)
    {
    }

    /** Расчет простоев по машинам
     * @param Request $request
     * @return Response|\Inertia\Response|BinaryFileResponse
     */
    public function machinesIdles(Request $request): Response|BinaryFileResponse|\Inertia\Response
    {
        $dates = request(['start_date', 'finish_date']);
        $idlesData = $dates ? $this->prepareIdleReportData($dates, $request->machines) : [];
        if (request('convertToExcel')) {
            return $this->excelService->convertIdlesToExcel($dates, $idlesData);
        } else {
            return Inertia::render('Reports/MachinesIdlePage', [
                'idlesData' => $idlesData
            ]);
        }
    }

    /** Подготовка расчета данных простоев
     * @param $dates
     * @param $machine_types
     * @return array
     */
    private function prepareIdleReportData(array $dates, array $machine_types): array
    {
        // Валидация входных данных
        if (!isset($dates['start_date']) || !isset($dates['finish_date'])) {
            throw new \InvalidArgumentException('Start date and finish date must be provided.');
        }

        $startDate = $dates['start_date'];
        $endDate = $dates['finish_date'];
        $idlesData = [];

        // Фильтруем активные типы машин
        $activeMachineTypes = array_filter($machine_types, fn($value) => $value === true);

        // Собираем данные о простоях для каждого активного типа
        foreach (array_keys($activeMachineTypes) as $machineType) {
            $idlesData[$machineType] = $this->machinesType->getTypeIdles($machineType, $startDate, $endDate);
        }

        return $idlesData;
    }

}
