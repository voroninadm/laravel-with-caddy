<?php

namespace App\Http\Controllers\Elog;

use App\Helpers\ElogMachinesTasksHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ElogTask\CuttingTaskRequest;
use App\Models\Elog\Cutting;
use App\Services\ElogMachinesFilterService;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class CuttingController extends Controller
{
    private $all_workers, $workers, $all_machines, $current_machines, $materials;

    public function __construct(
        protected ElogMachinesTasksHelper $tasksService,
        protected ExcelExportService      $excelService)
    {
        $this->all_workers = $this->tasksService->getAllWorkersNames();
        $this->workers = $this->tasksService->getCurrentWorkersNames();
        $this->all_machines = $this->tasksService->getAllMachinesTitles('cutting');
        $this->current_machines = $this->tasksService->getCurrentMachinesTitles('cutting');
        $this->materials = $this->tasksService->getMaterialsNames('material');
    }

    public function create()
    {
        return Inertia::render('Elog/Cutting/CreatePage', [
            'workers' => $this->workers,
            'machines' => $this->current_machines,
            'materials' => $this->materials,
            'productTypes' => config('constants.elog_cutting_products')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CuttingTaskRequest $request)
    {
        Cutting::create($request->validated());

        return to_route('cutting.create');
    }

    public function edit(Cutting $task): Response
    {
        return Inertia::render('Elog/Cutting/EditPage', [
            'workers' => $this->workers,
            'machines' => $this->current_machines,
            'materials' => $this->materials,
            'productTypes' => config('constants.elog_cutting_products'),
            'cuttingTask' => $task,
        ]);
    }

    public function update(CuttingTaskRequest $request, Cutting $task)
    {
        $task->update($request->validated());

        return redirect()->back();
    }

    public function destroy(Cutting $task)
    {
        $task->delete();

        return redirect()->back();
    }

    /** Все работы
     * @param Cutting $cutting
     * @param Request $request
     * @param ElogMachinesFilterService $filter
     * @return Response|BinaryFileResponse
     */
    public function allWorks(Cutting $cutting, Request $request, ElogMachinesFilterService $filter): Response|BinaryFileResponse
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $cutting) : null;

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertAllTasksToExcel('cutting',$dates, $tasks);
        } else {
            return Inertia::render('Elog/Cutting/AllWorksPage', [
                'machines' => $this->current_machines,
                'workers' => $this->all_workers,
                'tasks' => $tasks
            ]);
        }
    }

    /**
     * @param Cutting $cutting
     * @param Request $request
     * @param ElogMachinesFilterService $filter
     * @return Response|BinaryFileResponse
     */
    public function worksReport(Cutting $cutting, Request $request, ElogMachinesFilterService $filter): Response|BinaryFileResponse
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $cutting) : [];
        $data = $dates ? $this->tasksService->getReportData($cutting, $tasks) : [];

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertWorksReportTasksToExcel('cutting',$dates, $data);
        } else {
            return Inertia::render('Elog/Cutting/WorksReportPage', [
                'machines' => $this->all_machines,
                'workers' => $this->all_workers,
                'data' => $data
            ]);
        }
    }

    /**
     * @param Cutting $cutting
     * @param Request $request
     * @param ElogMachinesFilterService $filter
     * @return Response|BinaryFileResponse
     */
    public function workout(Cutting $cutting, Request $request, ElogMachinesFilterService $filter): Response|BinaryFileResponse
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $cutting) : null;

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertWorkoutTasksToExcel('cutting',$dates, $tasks);
        } else {
            return Inertia::render('Elog/Cutting/WorkoutPage', [
                'machines' => $this->current_machines,
                'workers' => $this->all_workers,
                'tasks' => $tasks
            ]);
        }
    }
}
