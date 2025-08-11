<?php

namespace App\Console\Commands;

use App\Http\Repositories\EncostRepository;
use App\Models\MachinesType;
use App\Notifications\Encost\EncostRequestHasChangesNotification;
use App\Notifications\Encost\EncostRequestErrorNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class EncostIdlesCommand extends Command
{
    protected $signature = 'app:encost-idles-command';

    protected $description = 'Проверка списка простоев типов машин (encost hierarchy)';

    public function __construct(public EncostRepository $repository)
    {
        parent::__construct();
    }


    public function handle()
    {
        $machine_types = MachinesType::where('encost_type', '!=', null)->get();

        try {
            foreach ($machine_types as $machine_type) {
                $filePath = 'responses/';
                $fileName = $machine_type->name . '_idles.json';

                $response = $this->repository->getEncostHierarchyIdles($machine_type->encost_type);
                if ($response->getStatusCode() !== 200) {
                    Notification::route('telegram', 'TELEGRAM_CHAT_ID')
                        ->notify(new EncostRequestErrorNotification("Ошибка при получении простоев" . $machine_type->title . ", код " . $response->getStatusCode()));
                    return;
                }

                $responseData = json_decode($response->getBody()->getContents(), true);

                // Проверяем и создаем папку, если её нет
                Storage::disk('local')->makeDirectory('responses');

                // Если файл существует, сравниваем содержимое
                if (Storage::disk('local')->exists($filePath . $fileName)) {
                    $oldData = json_decode(Storage::disk('local')->get($filePath . $fileName), true);

                    if (json_encode($oldData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !==
                        json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) {

                        Notification::route('telegram', 'TELEGRAM_CHAT_ID')
                            ->notify(new EncostRequestHasChangesNotification($machine_type->title  . " - структура простоев изменилась!"));
                    }
                    Storage::disk('local')->put($filePath . 'old_' . $fileName, json_encode($oldData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                }

                // Сохраняем новые данные
                Storage::disk('local')->put($filePath . $fileName, json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

            }
        } catch (\Exception $e) {
            Log::error('Ошибка: ' . $e->getMessage());
            Notification::route('telegram', 'TELEGRAM_CHAT_ID')
                ->notify(new EncostRequestErrorNotification("Ошибка при получении и сравнении простоев типа - " . $machine_type->title));
        }
    }
}
