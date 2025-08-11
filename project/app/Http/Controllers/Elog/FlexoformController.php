<?php

namespace App\Http\Controllers\Elog;

use App\Helpers\ElogMachinesTasksHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ElogTask\FlexoformTaskRequest;
use App\Models\Elog\Flexoform;
use App\Services\ElogMachinesFilterService;
use App\Services\ExcelExportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FlexoformController extends Controller
{
    private $all_workers, $workers, $print_machines, $flexoforms_machines, $current_machines, $sticky_tape;

    public function __construct(
        protected ElogMachinesTasksHelper $tasksService,
        protected ExcelExportService      $excelService)
    {
        $this->all_workers = $this->tasksService->getAllWorkersNames();
        $this->workers = $this->tasksService->getCurrentWorkersNames();
        $this->flexoforms_machines = $this->tasksService->getCurrentMachinesTitles('flexoform');
        $this->print_machines = $this->tasksService->getCurrentMachinesTitles('printing');
        $this->sticky_tape = config('constants.elog_flexoform_sticky_tape');
    }

    public function create(): Response
    {
        return Inertia::render('Elog/Flexoform/CreatePage', [
            'workers' => $this->workers,
            'print_machines' => $this->print_machines,
            'flexoforms_machines' => $this->flexoforms_machines,
            'sticky_tape' => $this->sticky_tape,
        ]);
    }

    public function store(FlexoformTaskRequest $request)
    {
        Flexoform::create($request->validated());

        return to_route('flexoform.create');
    }

    public function edit(Flexoform $task): Response
    {
        return Inertia::render('Elog/Flexoform/EditPage', [
            'workers' => $this->workers,
            'print_machines' => $this->print_machines,
            'flexoforms_machines' => $this->flexoforms_machines,
            'sticky_tape' => $this->sticky_tape,
            'flexoformTask' => $task,
        ]);
    }

    public function update(FlexoformTaskRequest $request, Flexoform $task): RedirectResponse
    {
        $task->update($request->validated());
        return redirect()->back();
    }

    public function destroy(Flexoform $task): RedirectResponse
    {
        $task->delete();
        return redirect()->back();
    }

    public function allWorks(Flexoform $flexoform, Request $request, ElogMachinesFilterService $filter)
    {
        $dates = request(['start_date', 'finish_date']);
        $tasks = $dates ? $filter($request, $flexoform) : null;

        if ($request->has('convertToExcel')) {
            return $this->excelService->convertAllTasksToExcel('flexoform',$dates, $tasks);
        } else {
            return Inertia::render('Elog/Flexoform/AllWorksPage', [
                'machines' => $this->flexoforms_machines,
                'workers' => $this->all_workers,
                'tasks' => $tasks
            ]);
        }
    }


}
