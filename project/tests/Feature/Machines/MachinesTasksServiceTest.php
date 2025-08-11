<?php

use App\Helpers\ElogMachinesTasksHelper;
use App\Models\Elog\Cutting;
use App\Models\Elog\Extrusion;
use App\Models\Elog\Lamination;
use App\Models\Elog\Printing;
use App\Models\Elog\Recycling;
use App\Models\Elog\Welding;
use App\Models\MachinesType;
use App\Services\ElogMachinesFilterService;
use Illuminate\Http\Request;


describe('machines task service test', function () {

    beforeEach(function () {
        $this->service = new ElogMachinesTasksHelper(new MachinesType());
        $this->filter = new ElogMachinesFilterService();
    });

    it('allMachinesTitles are cached at request', function (string $machineType) {
        $request = $this->service->getAllMachinesTitles($machineType);
        expect($request)->not->toBeEmpty();
    })->with('MachinesTypes');

    it('currentMachinesTitles are cached at request', function (string $machineType) {
        $request = $this->service->getCurrentMachinesTitles($machineType);
        expect($request)->not->toBeEmpty();
    })->with('MachinesTypes');

    it('allWorkersNames are cached at request', function () {
        $request = $this->service->getAllWorkersNames();
        expect($request)->not->toBeEmpty();
    });

    it('currentWorkersNames are cached at request', function () {
        $request = $this->service->getCurrentWorkersNames();
        expect($request)->not->toBeEmpty();
    });

    it('materialsNames are cached at request', function (string $materialType) {
        $request = $this->service->getMaterialsNames($materialType);
        expect($request)->not->toBeEmpty();
    })->with('MaterialTypes');


    it('can filter machines tasks by date', function (string $machineClass) {
        $request = new Request([
            'start_date' => '2025-01-01',
            'finish_date' => '2025-01-10',
            'machine' => null,
            'worker' => null,
            'tkn' => null,
        ]);
        $filterHelper = new ElogMachinesFilterService();
        $filteredTasks = $filterHelper($request, $machineClass);
        expect($filteredTasks)->not->toBeEmpty();
    })->with('MachinesClasses')->group('machinesTaskTests');

    it('can_filter_machines_tasks_by_date_and_tkn', function (string $machineClass) {
        $request = new Request([
            'start_date' => '2025-01-01',
            'finish_date' => '2025-01-10',
            'machine' => null,
            'worker' => null,
            'tkn' => '12345a',
        ]);
        $filterHelper = new ElogMachinesFilterService();
        $filteredTasks = $filterHelper($request, $machineClass);
        expect($filteredTasks)->not->toBeEmpty();
    })->with('MachinesClasses')->group('machinesTaskTests');

    it('can_filter_machines_tasks_by_date_and_master_and_tkn--empty', function (string $machineClass) {
        $request = new Request([
            'start_date' => '2025-01-01',
            'finish_date' => '2025-01-10',
            'machine' => null,
            'worker' => 'Карелина С.Н.',
            'tkn' => '12345a',
        ]);
        $filterHelper = new ElogMachinesFilterService();
        $filteredTasks = $filterHelper($request, $machineClass);
        expect($filteredTasks)->toBeEmpty();
    })->with('MachinesClasses')->group('machinesTaskTests');

    it('can_filter_machines_tasks_by_date_and_master_and_tkn', function (string $machineClass) {
        $request = new Request([
            'start_date' => '2025-01-01',
            'finish_date' => '2025-01-10',
            'machine' => null,
            'worker' => 'Поцко С.С.',
            'tkn' => '12345a',
        ]);
        $filterHelper = new ElogMachinesFilterService();
        $filteredTasks = $filterHelper($request, $machineClass);
        expect($filteredTasks)->not->toBeEmpty();
    })->with('MachinesClasses')->group('machinesTaskTests');

    it('full_printing_filter', function () {
        $class = new Printing();
        $request = new Request([
            'start_date' => '2025-01-01',
            'finish_date' => '2025-01-10',
            'machine' => 'Allstein',
            'worker' => 'Поцко С.С.',
            'tkn' => '12345a',
        ]);
        $filterHelper = new ElogMachinesFilterService();
        $filteredTasks = $filterHelper($request, $class);
        expect($filteredTasks)->not->toBeEmpty();
    });

    it('full_lamination_filter', function () {
        $class = new Lamination();
        $request = new Request([
            'start_date' => '2025-01-01',
            'finish_date' => '2025-01-10',
            'machine' => 'Ламинатор 1',
            'worker' => 'Поцко С.С.',
            'tkn' => '12345a',
        ]);
        $filterHelper = new ElogMachinesFilterService();
        $filteredTasks = $filterHelper($request, $class);
        expect($filteredTasks)->not->toBeEmpty();
    });

    it('full_welding_filter', function () {
        $class = new Welding();
        $request = new Request([
            'start_date' => '2025-01-01',
            'finish_date' => '2025-01-10',
            'machine' => 'Интермат-3',
            'worker' => 'Поцко С.С.',
            'tkn' => '12345a',
        ]);
        $filterHelper = new ElogMachinesFilterService();
        $filteredTasks = $filterHelper($request, $class);
        expect($filteredTasks)->not->toBeEmpty();
    });

    it('full_cutting_filter', function () {
        $class = new Cutting();
        $request = new Request([
            'start_date' => '2025-01-01',
            'finish_date' => '2025-01-10',
            'machine' => 'Бобинорезка-3',
            'worker' => 'Поцко С.С.',
            'tkn' => '12345a',
        ]);
        $filterHelper = new ElogMachinesFilterService();
        $filteredTasks = $filterHelper($request, $class);
        expect($filteredTasks)->not->toBeEmpty();
    });

    it('full_extrusion_filter', function () {
        $class = new Extrusion();
        $request = new Request([
            'start_date' => '2025-01-01',
            'finish_date' => '2025-01-10',
            'machine' => 'Экструдер-20',
            'worker' => 'Поцко С.С.',
            'tkn' => '12345a',
        ]);
        $filterHelper = new ElogMachinesFilterService();
        $filteredTasks = $filterHelper($request, $class);
        expect($filteredTasks)->not->toBeEmpty();
    });

    it('full_recycling_filter', function () {
        $class = new Recycling();
        $request = new Request([
            'start_date' => '2025-01-01',
            'finish_date' => '2025-01-10',
            'machine' => 'Ерёма-1',
            'worker' => 'Поцко С.С.',
            'nomenclature' => 'GPE-M'
        ]);
        $filterHelper = new ElogMachinesFilterService();
        $filteredTasks = $filterHelper($request, $class);
        expect($filteredTasks)->not->toBeEmpty();
    });
});
