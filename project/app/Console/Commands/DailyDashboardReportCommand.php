<?php

namespace App\Console\Commands;

use App\Notifications\Dashboard\ReportCreated;
use App\Notifications\Dashboard\ReportError;
use AppServices\DashboardReportService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class DailyDashboardReportCommand extends Command
{
    protected $signature = 'app:dashboard_daily_report';
    protected $description = 'Generate daily report for dashboard';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $dashboardHelper = new DashboardReportService();
        $now = Carbon::now();
        $yesterdayStart = $now->copy()->subDay()->setTime(8, 0);
        $todayEnd = $now->copy()->setTime(8, 0);

        try {
            $todayReport = $dashboardHelper->getDailyReport($yesterdayStart, $todayEnd);
            $yesterdayReport = Cache::get('dashboard_report');
            if($yesterdayReport) {
                $todayReport = $this->compareDailyReports($yesterdayReport, $todayReport);
            }

            Cache::put('dashboard_report', $todayReport, now()->addDays(2));

            Notification::route('telegram', 'TELEGRAM_CHAT_ID')
                ->notify(new ReportCreated($todayEnd));

        } catch (\Throwable $e) {
            Notification::route('telegram', 'TELEGRAM_CHAT_ID')
                ->notify(new ReportError($todayEnd, $e->getMessage()));
        }
    }

    private function compareDailyReports($oldData ,$newData): array
    {
        $report = [];

        foreach ($newData as $key => $value) {
            $status = 'no change';
            if (array_key_exists($key, $oldData)) {
                if ($value > $oldData[$key]) {
                    $status = 'up';
                } elseif ($value < $oldData[$key]) {
                    $status = 'down';
                }
            }
            $report[$key] = $value; // Сохраняем значение из нового массива
            $report[$key . '_status'] = $status; // Добавляем статус
        }
        return $report;
    }
}
