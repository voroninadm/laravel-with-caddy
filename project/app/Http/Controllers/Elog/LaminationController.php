<?php

namespace App\Http\Controllers\Elog;

use App\Helpers\ElogMachinesTasksHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ElogTask\LaminationTaskRequest;
use App\Models\Elog\Lamination;
use App\Services\ElogMachinesFilterService;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LaminationController extends Controller
{
    private $all_workers, $workers, $all_machines, $current_machines, $materials, $coverings;

    public function __construct(
        protected ElogMachinesTasksHelper $tasksService,
        protected ExcelExportService      $excelService)
    {
        $this->all_workers = $this->tasksService->getAllWorkersNames();
        $this->workers = $this->tasksService->getCurrentWorkersNames();
        $this->all_machines = $this->tasksService->getAllMachinesTitles('lamination');
        $this->current_machines = $this->tasksService->getCurrentMachinesTitles('lamination');
        $this->materials = $this->tasksService->getMaterialsNames('material');
        $this->coverings = $this->tasksService->getMaterialsNames('covering');
    }

    public function create()
    {
        return Inertia::render('Elog/Lamination/CreatePage', [
            'workers' => $this->workers,
            'machines' => $this->current_machines,
            'materials' => $this->materials,
            'coverings' => $this->coverings,
        ]);
    }

    public function store(LaminationTaskRequest $request)
    {
        Lamination::create($request->validated());

        return to_route('lamination.create');
    }

    public function edit(Lamination $task): \Inertia\Response
    {
        return Inertia::render('Elog/Lamination/EditPage', [
            'workers' => $this->workers,
            'machines' => $this->current_machines,
            'materials' => $this->materials,
            'coverings' => $this->coverings,
            'lamTask' => $task,
        ]);
    }

    public function update(LaminationTaskRequest $request, Lamination $task)
    {
        $task->update($request->validated());
        return redirect()->back();
    }

    public function destroy(Lamination $task)
    {
        $task->delete();

        return redirect()->back();
    }

    /** Все работы
     * @param Lamination $lamination
     * @param Request $request
     * @return Response|BinaryFileResponse
     */
    public function allWorks(Lamination $lamination, Request $request, ElogMachinesFilterService $filter): Response|BinaryFileResponse
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $lamination) : null;

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertAllTasksToExcel('lamination',$dates, $tasks);
        } else {
            return Inertia::render('Elog/Lamination/AllWorksPage', [
                'machines' => $this->current_machines,
                'workers' => $this->all_workers,
                'tasks' => $tasks
            ]);
        }
    }

    /**
     * @param Lamination $lamination
     * @param Request $request
     * @return Response|BinaryFileResponse
     */
    public function worksReport(Lamination $lamination, Request $request, ElogMachinesFilterService $filter): Response|BinaryFileResponse
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $lamination) : [];
        $data = $dates ? $this->tasksService->getReportData($lamination, $tasks) : [];

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertWorksReportTasksToExcel('lamination',$dates, $data);
        } else {
            return Inertia::render('Elog/Lamination/WorksReportPage', [
                'machines' => $this->all_machines,
                'workers' => $this->all_workers,
                'data' => $data
            ]);
        }
    }

    /**
     * @param Lamination $lamination
     * @param Request $request
     * @return Response|BinaryFileResponse
     */
    public function workout(Lamination $lamination, Request $request, ElogMachinesFilterService $filter): Response|BinaryFileResponse
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $lamination) : null;

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertWorkoutTasksToExcel('lamination',$dates, $tasks);
        } else {
            return Inertia::render('Elog/Lamination/WorkoutPage', [
                'machines' => $this->current_machines,
                'workers' => $this->all_workers,
                'tasks' => $tasks
            ]);
        }
    }
}
