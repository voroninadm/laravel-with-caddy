<?php

namespace App\Http\Controllers\Elog;

use App\Helpers\ElogMachinesTasksHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ElogTask\PrintingTaskRequest;
use App\Models\Elog\Printing;
use App\Services\ElogMachinesFilterService;
use App\Services\ExcelExportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class PrintingController extends Controller
{
    private $all_workers, $workers, $all_machines, $current_machines, $materials;

    public function __construct(
        protected ElogMachinesTasksHelper $tasksService,
        protected ExcelExportService      $excelService)
    {
        $this->all_workers = $this->tasksService->getAllWorkersNames();
        $this->workers = $this->tasksService->getCurrentWorkersNames();
        $this->all_machines = $this->tasksService->getAllMachinesTitles('printing');
        $this->current_machines = $this->tasksService->getCurrentMachinesTitles('printing');
        $this->materials = $this->tasksService->getMaterialsNames('material');
    }

    public function create(): Response
    {
        return Inertia::render('Elog/Printing/CreatePage', [
            'workers' => $this->workers,
            'machines' => $this->current_machines,
            'materials' => $this->materials,
        ]);
    }

    public function store(PrintingTaskRequest $request): RedirectResponse
    {
        Printing::create($request->validated());

        return to_route('printing.create');
    }

    public function edit(Printing $task): Response
    {
        return Inertia::render('Elog/Printing/EditPage', [
            'workers' => $this->workers,
            'machines' => $this->current_machines,
            'materials' => $this->materials,
            'printTask' => $task,
        ]);
    }

    public function update(PrintingTaskRequest $request, Printing $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()->back();
    }

    public function destroy(Printing $task): RedirectResponse
    {
        $task->delete();

        return redirect()->back();
    }

    /** Все работы
     * @param Printing $printing
     * @param Request $request
     * @param ElogMachinesFilterService $filter
     * @return Response|BinaryFileResponse
     */
    public function allWorks(Printing $printing, Request $request, ElogMachinesFilterService $filter): Response|BinaryFileResponse
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $printing) : null;

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertAllTasksToExcel('printing',$dates, $tasks);
        } else {
            return Inertia::render('Elog/Printing/AllWorksPage', [
                'machines' => $this->current_machines,
                'workers' => $this->all_workers,
                'tasks' => $tasks
            ]);
        }
    }

    /**
     * @param Printing $printing
     * @param Request $request
     * @return Response|BinaryFileResponse
     */
    public function worksReport(Printing $printing, Request $request, ElogMachinesFilterService $filter): Response|BinaryFileResponse
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $printing) : [];
        $data = $dates ? array_merge($this->tasksService->getReportData($printing, $tasks)) : [];

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertWorksReportTasksToExcel('printing',$dates, $data);
        } else {
            return Inertia::render('Elog/Printing/WorksReportPage', [
                'machines' => $this->all_machines,
                'workers' => $this->all_workers,
                'data' => $data
            ]);
        }
    }

    /** Выработка
     * @param Printing $printing
     * @param Request $request
     * @return \Illuminate\Http\Response|BinaryFileResponse
     */
    public function workout(Printing $printing, Request $request, ElogMachinesFilterService $filter): Response|BinaryFileResponse
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $printing) : null;

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertWorkoutTasksToExcel('printing',$dates, $tasks);
        } else {
            return Inertia::render('Elog/Printing/WorkoutPage', [
                'machines' => $this->current_machines,
                'workers' => $this->all_workers,
                'tasks' => $tasks
            ]);
        }
    }
}
