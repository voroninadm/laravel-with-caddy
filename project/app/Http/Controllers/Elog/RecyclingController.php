<?php

namespace App\Http\Controllers\Elog;

use App\Helpers\ElogMachinesTasksHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ElogTask\RecyclingTaskRequest;
use App\Models\Elog\Recycling;
use App\Services\ElogMachinesFilterService;
use App\Services\ExcelExportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RecyclingController extends Controller
{
    private $all_workers, $workers, $all_machines, $current_machines, $granulate, $ingots;

    public function __construct(
        protected ElogMachinesTasksHelper $tasksService,
        protected ExcelExportService      $excelService)
    {
        $this->all_workers = $this->tasksService->getAllWorkersNames();
        $this->workers = $this->tasksService->getCurrentWorkersNames();
        $this->all_machines = $this->tasksService->getAllMachinesTitles('recycling');
        $this->current_machines = $this->tasksService->getCurrentMachinesTitles('recycling');
        $this->granulate = $this->tasksService->getMaterialsNames('granulate');
        $this->ingots = $this->tasksService->getMaterialsNames('ingot');
    }

    public function create(): Response
    {
        return Inertia::render('Elog/Recycling/CreatePage', [
            'workers' => $this->workers,
            'machines' => $this->current_machines,
            'granulates' => $this->granulate,
            'ingots_types' => $this->ingots,
        ]);
    }

    public function store(RecyclingTaskRequest $request): RedirectResponse
    {
        Recycling::create($request->validated());

        return to_route('recycling.create');
    }

    public function edit(Recycling $task): Response
    {
        return Inertia::render('Elog/Recycling/EditPage', [
            'workers' => $this->workers,
            'machines' => $this->current_machines,
            'granulates' => $this->granulate,
            'ingots_types' => $this->ingots,
            'recyclingTask' => $task,
        ]);
    }

    public function update(RecyclingTaskRequest $request, Recycling $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()->back();
    }

    public function destroy(Recycling $task): RedirectResponse
    {
        $task->delete();

        return redirect()->back();
    }

    public function allWorks(Recycling $recycling, Request $request, ElogMachinesFilterService $filter): Response|BinaryFileResponse
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $recycling) : null;

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertAllTasksToExcel('recycling',$dates, $tasks);
        } else {
            return Inertia::render('Elog/Recycling/AllWorksPage', [
                'machines' => $this->current_machines,
                'workers' => $this->all_workers,
                'tasks' => $tasks,
                'nomenclatures' => array_merge($this->granulate, $this->ingots)
            ]);
        }
    }

    public function worksReport(Recycling $recycling, Request $request, ElogMachinesFilterService $filter): Response|BinaryFileResponse
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $recycling) : [];
        $data = $dates ? array_merge($this->tasksService->getReportData($recycling, $tasks)) : [];

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertWorksReportTasksToExcel('recycling',$dates, $data);
        } else {
            return Inertia::render('Elog/Recycling/WorksReportPage', [
                'machines' => $this->all_machines,
                'nomenclatures' => array_merge($this->granulate, $this->ingots),
                'workers' => $this->all_workers,
                'data' => $data
            ]);
        }
    }

    public function workout(Recycling $recycling, Request $request, ElogMachinesFilterService $filter): BinaryFileResponse|Response
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $recycling) : null;

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertWorkoutTasksToExcel('recycling', $dates, $tasks);
        } else {
            return Inertia::render('Elog/Recycling/WorkoutPage', [
                'machines' => $this->current_machines,
                'workers' => $this->all_workers,
                'tasks' => $tasks
            ]);
        }
    }
}
