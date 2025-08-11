<?php

namespace App\Http\Controllers;

use App\Events\BroadcastOrderUpdatedEvent;
use App\Helpers\ElogMachinesTasksHelper;
use App\Http\Requests\OrderRequest;
use App\Models\JsonOrder;
use App\Models\MachinesType;
use App\Services\ExcelExportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class JsonOrderController extends Controller
{
    private $workers;
    public function __construct(
        public MachinesType $mtype,
        public ExcelExportService $excelService,
        protected ElogMachinesTasksHelper $tasksService
    )
    {
        $this->workers = $this->tasksService->getCurrentWorkersNames();
    }

    public function orders(Request $request): \Inertia\Response
    {
        $machineType = $request->machineType;
        $order = [];

        if (request('machineType')) {
            $machines = $this->mtype->getMachines($request->machineType)->orderBy('queue_order')->pluck('title')->toArray();
            $order = $this->filterJsonOrders($request);
            $spoolcut_info = $request->spoolcutter ? $this->calculateSpoolWidthAndCount($order, $request->spoolcutter) : null;
        }

        return Inertia::render('JsonOrder/JsonOrder', [
            'machines' => $machines ?? null,
            'workers' => $this->workers,
            'machineType' => $machineType,
            'order' => $order,
            'searchedOrder' => $request->tkn ?? null,
            'searchedSpoolcutter' => $request->spoolcutter ?? '',
            'spoolcutterWorkout' => $spoolcut_info ?? []
        ]);
    }

    /**
     * @throws \Exception
     */
    public function update(OrderRequest $request, JsonOrder $order): RedirectResponse
    {
        $validated_request = $request->validated();
        try {
            DB::beginTransaction();
            $currentUserId = auth()->user()->id;
            $orderType = $validated_request['filteredUpdate'];
            $updatedMachine = $validated_request['machine'];
            $orderMachineTypeArray = $order->{$orderType};
            $orderMachineTypeArray[$updatedMachine] = $validated_request['payload'];
            $order->{$orderType} = $orderMachineTypeArray;
            $order->update();
            DB::commit();
            broadcast(new BroadcastOrderUpdatedEvent($currentUserId, $order->id, $orderType, $updatedMachine, $orderMachineTypeArray[$updatedMachine]))
            ->toOthers();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
        return redirect()->back();
    }


    private function filterJsonOrders(Request $request): Collection
    {
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $finish_date = date('Y-m-d', strtotime($request->finish_date));
        $machine_type = $request->machineType;
        $tkn = $request->tkn;
        $spoolcutter = $request->spoolcutter;

        $query = JsonOrder::query();
        $query->whereBetween('date', [$start_date, $finish_date]);
        $query->whereNotNull($machine_type);
        $query->select('id', 'date', $machine_type);
        $query->orderBy('date', 'asc');

        $typeId = $this->mtype->getIdByType($machine_type);
        if ($typeId) {
            $machinesList = $this->mtype->getMachines($machine_type)->orderBy('queue_order')->pluck('title');

            $query->where(function ($query) use ($machine_type, $machinesList, $tkn, $spoolcutter) {
                foreach ($machinesList as $machine) {
                    if ($tkn && $spoolcutter) {
                        // Проверяем, что оба параметра присутствуют
                        $query->orWhere(function ($query) use ($machine_type, $machine, $tkn, $spoolcutter) {
                            $query->whereRaw("JSON_SEARCH(`$machine_type`, 'one', '$tkn', NULL, '$.\"$machine\"[*].CARD_NUMBER') IS NOT NULL")
                                ->whereRaw("JSON_SEARCH(`$machine_type`, 'one', '$spoolcutter', NULL, '$.\"$machine\"[*].SPOOLCUTTER') IS NOT NULL");
                        });
                    } elseif ($tkn) {
                        // Проверяем, что присутствует только tkn
                        $query->orWhereRaw("JSON_SEARCH(`$machine_type`, 'one', '$tkn', NULL, '$.\"$machine\"[*].CARD_NUMBER') IS NOT NULL");
                    } elseif ($spoolcutter) {
                        // Проверяем, что присутствует только spoolcutter
                        $query->orWhereRaw("JSON_SEARCH(`$machine_type`, 'one', '$spoolcutter', NULL, '$.\"$machine\"[*].SPOOLCUTTER') IS NOT NULL");
                    }
                }
            });
        } else {
            return collect();
        }

        return $query->get();
    }


    private function calculateSpoolWidthAndCount(Collection $orders, $spoolcutter): array
    {
        $spoolcutterData = [];
        $spoolcutterData['spoolcutter'] = $spoolcutter;
        $spoolWidthData = [];

        foreach ($orders as $order) {
            foreach ($order['spoolcutting'] as $machineName => $machineData) {
                foreach ($machineData as $data) {
                    if (isset($data['SPOOLCUTTER']) && $data['SPOOLCUTTER'] == $spoolcutter && isset($data['SPOOL_WIDTH'])) {
                        $width = (int) $data['SPOOL_WIDTH'];
                        $spoolsCount = (int) $data['SPOOL_FACT'];
                        // Инициализируем данные для каждой ширины, если ещё не сделано
                        if (!isset($spoolWidthData[$width])) {
                            $spoolWidthData[$width] = 0;
                        }
                        $spoolWidthData[$width] += $spoolsCount;
                    }
                }
            }
        }
        arsort($spoolWidthData);
        $spoolcutterData['workout'] = $spoolWidthData;
        return $spoolcutterData;
    }

    public function spoolcutterReportExport(Request $request): Response|BinaryFileResponse
    {
        $dates = $request->dates;
        $spoolcutterData = $request->spoolcutterData;
        return $this->excelService->convertSpoolcutterOrdersToExcel($dates, $spoolcutterData);
    }
}
