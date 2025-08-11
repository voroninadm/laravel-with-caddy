<?php

namespace App\Http\Controllers\Elog;


use App\Helpers\ElogMachinesTasksHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ElogTask\ExtrusionTaskRequest;
use App\Models\Elog\Extrusion;
use App\Services\ElogMachinesFilterService;
use App\Services\ExcelExportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class ExtrusionController extends Controller
{
    private $all_workers, $workers, $all_machines, $current_machines;

    public function __construct(
        protected ElogMachinesTasksHelper $tasksService,
        protected ExcelExportService      $excelService)
    {
        $this->all_workers = $this->tasksService->getAllWorkersNames();
        $this->workers = $this->tasksService->getCurrentWorkersNames();
        $this->all_machines = $this->tasksService->getAllMachinesTitles('extrusion');
        $this->current_machines = $this->tasksService->getCurrentMachinesTitles('extrusion');
    }

    public function create(): Response
    {
        return Inertia::render('Elog/Extrusion/CreatePage', [
            'workers' => $this->workers,
            'machines' => $this->current_machines,
            'productTypes' => config('constants.elog_extrusion_products'),
        ]);
    }

    public function store(ExtrusionTaskRequest $request): RedirectResponse
    {
        Extrusion::create($request->validated());

        return to_route('extrusion.create');
    }

    public function edit(Extrusion $task): Response
    {
        return Inertia::render('Elog/Extrusion/EditPage', [
            'workers' => $this->workers,
            'machines' => $this->current_machines,
            'productTypes' => config('constants.elog_extrusion_products'),
            'extrusionTask' => $task,
        ]);
    }

    public function update(ExtrusionTaskRequest $request, Extrusion $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()->back();
    }

    public function destroy(Extrusion $task)
    {
        $task->delete();

        return redirect()->back();
    }


    public function allWorks(Extrusion $extrusion, Request $request, ElogMachinesFilterService $filter)
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $extrusion) : null;

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertAllTasksToExcel('extrusion',$dates, $tasks);
        } else {
            return Inertia::render('Elog/Extrusion/AllWorksPage', [
                'machines' => $this->current_machines,
                'workers' => $this->all_workers,
                'tasks' => $tasks
            ]);
        }
    }

    /**
     * @param Extrusion $extrusion
     * @param Request $request
     * @param ElogMachinesFilterService $filter
     * @return Response|BinaryFileResponse
     */
    public function worksReport(Extrusion $extrusion, Request $request, ElogMachinesFilterService $filter): Response|BinaryFileResponse
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $extrusion) : [];
        $data = $dates ? array_merge($this->tasksService->getReportData($extrusion, $tasks)) : [];

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertWorksReportTasksToExcel('extrusion',$dates, $data);
        } else {
            return Inertia::render('Elog/Extrusion/WorksReportPage', [
                'machines' => $this->all_machines,
                'workers' => $this->all_workers,
                'data' => $data
            ]);
        }
    }

    public function workout(Extrusion $extrusion, Request $request, ElogMachinesFilterService $filter): BinaryFileResponse|Response
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $extrusion) : null;

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertWorkoutTasksToExcel('extrusion', $dates, $tasks);
        } else {
            return Inertia::render('Elog/Extrusion/WorkoutPage', [
                'machines' => $this->current_machines,
                'workers' => $this->all_workers,
                'tasks' => $tasks
            ]);
        }
    }
}
