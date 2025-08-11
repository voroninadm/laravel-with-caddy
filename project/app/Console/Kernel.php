<?php

namespace App\Console;

use App\Notifications\BackupCleaned;
use App\Notifications\BackupSuccessful;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Notification;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        if (app()->isProduction()) {
            $schedule->command('app:make-clean-orders-schedule') // cоздание структуру распоряжений
            ->daily()->at('10:00');

            $schedule->command('app:dashboard_daily_report') //  Dashboard отчет
            ->daily()->at('09:00');

            $schedule->command('app:encost-machines-hierarchy-command') // Энкост проверка иерархии машин
            ->mondays()->thursdays()->at('19:00');

            $schedule->command('app:encost-idles-command')// Энкост проверка списка простоев по типам машин
            ->mondays()->thursdays()->at('19:00');
        }


        // чистим старые бэкапы
//        $schedule->command('backup:clean')
//            ->daily()->at('23:40')
//            ->onSuccess(function () {
//                Notification::route('telegram', 'TELEGRAM_CHAT_ID')
//                    ->notify(new BackupCleaned());
//            });

        // делаем бэкап БД. Если нужен бэкап и папки проекта - убрать ключ --only-db
//        $schedule->command('backup:run --only-db')
//            ->daily()->at('23:55')
//            ->onSuccess(function () {
//            Notification::route('telegram', 'TELEGRAM_CHAT_ID')
//                ->notify(new BackupSuccessful());
//        });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
