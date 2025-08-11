<?php

namespace App\Console\Commands;

use App\Events\BroadcastDailyScheduleCreatedEvent;
use App\Models\JsonOrder;
use App\Models\MachinesType;
use App\Notifications\Dashboard\ReportCreated;
use App\Notifications\Schedule\ScheduleAlreadyExist;
use App\Notifications\Schedule\ScheduleError;
use App\Notifications\Schedule\ScheduleUpdateSuccessful;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class MakeCleanOrdersSchedule extends Command
{
    public function __construct(public MachinesType $mtype)
{
    parent::__construct();
}

    protected $signature = 'app:make-clean-orders-schedule';

    protected $description = 'Создание чистого графика распоряжений на день';

    public function handle()
    {
        $dayToMakeScheduleRaw = Carbon::now()->addDays(6);
        $dayToMakeSchedule = $dayToMakeScheduleRaw->format('Y-m-d');
        $isAlreadyCreatedOrder = JsonOrder::where('date', '=', $dayToMakeSchedule)->exists();

        try {
            if (!$isAlreadyCreatedOrder) {
                $this->createOrderFromSchedule($dayToMakeSchedule);
                Notification::route('telegram', 'TELEGRAM_CHAT_ID')
                    ->notify(new ScheduleUpdateSuccessful($dayToMakeScheduleRaw));
            } else {
                Notification::route('telegram', 'TELEGRAM_CHAT_ID')
                    ->notify(new ScheduleAlreadyExist($dayToMakeScheduleRaw));
            }
        } catch (\Throwable $e) {

            Notification::route('telegram', 'TELEGRAM_CHAT_ID')
                ->notify(new ScheduleError($dayToMakeScheduleRaw, $e->getMessage()));
        }
    }

    /** Создаем график на день
     * @param $orderDate
     * @return void
     */
    private function createOrderFromSchedule($orderDate): void
    {
        $printingMachines = $this->mtype->getMachines('printing')->where('is_blocked', false)->pluck('title');
        $laminationMachines = $this->mtype->getMachines('lamination')->where('is_blocked', false)->pluck('title');
        $weldingMachines = $this->mtype->getMachines('welding')->where('is_blocked', false)->pluck('title');
        $cuttingMachines = $this->mtype->getMachines('cutting')->where('is_blocked', false)->pluck('title');
        $extrusionMachines = $this->mtype->getMachines('extrusion')->where('is_blocked', false)->pluck('title');
        $recyclingMachines = $this->mtype->getMachines('recycling')->where('is_blocked', false)->pluck('title');
        $spoolcuttingMachines = $this->mtype->getMachines('spoolcutting')->where('is_blocked', false)->pluck('title');

        $dailyPrint = $this->prepareMachineTemplate($printingMachines);
        $dailyLamination = $this->prepareMachineTemplate($laminationMachines);
        $dailyWelding = $this->prepareMachineTemplate($weldingMachines);
        $dailyCutting = $this->prepareMachineTemplate($cuttingMachines);
        $dailyExtrusion = $this->prepareMachineTemplate($extrusionMachines);
        $dailyRecycling = $this->prepareMachineTemplate($recyclingMachines);
        $dailySpoolcutting = $this->prepareMachineTemplate($spoolcuttingMachines);

        $newOrder = JsonOrder::create([
            'date' => date($orderDate),
            'printing' => $dailyPrint,
            'lamination' => $dailyLamination,
            'welding' => $dailyWelding,
            'cutting' => $dailyCutting,
            'extrusion' => $dailyExtrusion,
            'recycling' => $dailyRecycling,
            'spoolcutting' => $dailySpoolcutting,
        ]);

        broadcast(new BroadcastDailyScheduleCreatedEvent($newOrder));
    }

    /** Создаем шаблоны для типов машин
     * @param Collection $machines
     * @return array
     */
    private function prepareMachineTemplate(Collection $machines): array
    {
        $array = [];
        foreach ($machines as $machine) {
            $array[$machine] = [];
        }
        return $array;
    }
}
