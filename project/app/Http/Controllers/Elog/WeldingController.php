<?php

namespace App\Http\Controllers\Elog;

use App\Helpers\ElogMachinesTasksHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ElogTask\WeldingTaskRequest;
use App\Models\Elog\Welding;
use App\Services\ElogMachinesFilterService;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WeldingController extends Controller
{
    private $all_workers, $workers, $all_machines, $current_machines, $materials;

    public function __construct(
        protected ElogMachinesTasksHelper $tasksService,
        protected ExcelExportService      $excelService)
    {
        $this->all_workers = $this->tasksService->getAllWorkersNames();
        $this->workers = $this->tasksService->getCurrentWorkersNames();
        $this->all_machines = $this->tasksService->getAllMachinesTitles('welding');
        $this->current_machines = $this->tasksService->getCurrentMachinesTitles('welding');
        $this->materials = $this->tasksService->getMaterialsNames('material');
    }

    public function create()
    {
        return Inertia::render('Elog/Welding/CreatePage', [
            'workers' => $this->workers,
            'machines' => $this->current_machines,
            'materials' => $this->materials,
            'productTypes' => config('constants.elog_welding_products'),
            'bottomTypes' => config('constants.elog_welding_pocket_bottoms'),
            'boxesTypes' => config('constants.elog_welding_boxes'),
        ]);
    }

    public function store(WeldingTaskRequest $request)
    {
        Welding::create($request->validated());

        return to_route('welding.create');
    }

    public function edit(Welding $task): \Inertia\Response
    {
        return Inertia::render('Elog/Welding/EditPage', [
            'workers' => $this->workers,
            'machines' => $this->current_machines,
            'materials' => $this->materials,
            'weldingTask' => $task,
            'productTypes' => config('constants.elog_welding_products'),
            'bottomTypes' => config('constants.elog_welding_pocket_bottoms'),
            'boxesTypes' => config('constants.elog_welding_boxes'),
        ]);
    }

    public function update(WeldingTaskRequest $request, Welding $task)
    {
        $task->update($request->validated());
        return redirect()->back();
    }

    public function destroy(Welding $task)
    {
        $task->delete();
        return redirect()->back();
    }

    /** Все работы
     * @param Welding $welding
     * @param Request $request
     * @return Response|BinaryFileResponse
     */
    public function allWorks(Welding $welding, Request $request, ElogMachinesFilterService $filter): Response|BinaryFileResponse
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $welding) : null;

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertAllTasksToExcel('welding',$dates, $tasks);
        } else {
            return Inertia::render('Elog/Welding/AllWorksPage', [
                'machines' => $this->current_machines,
                'workers' => $this->all_workers,
                'tasks' => $tasks
            ]);
        }
    }

    /**
     * @param Welding $welding
     * @param Request $request
     * @return Response|BinaryFileResponse
     */
    public function worksReport(Welding $welding, Request $request, ElogMachinesFilterService $filter): Response|BinaryFileResponse
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $welding) : [];
        $data = $dates ? $this->tasksService->getReportData($welding, $tasks) : [];

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertWorksReportTasksToExcel('welding',$dates, $data);
        } else {
            return Inertia::render('Elog/Welding/WorksReportPage', [
                'machines' => $this->all_machines,
                'workers' => $this->all_workers,
                'data' => $data
            ]);
        }
    }

    public function workout(Welding $welding, Request $request, ElogMachinesFilterService $filter): Response|BinaryFileResponse
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $welding) : null;

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertWorkoutTasksToExcel('welding',$dates, $tasks);
        } else {
            return Inertia::render('Elog/Welding/WorkoutPage', [
                'machines' => $this->current_machines,
                'workers' => $this->all_workers,
                'tasks' => $tasks
            ]);
        }
    }
}
