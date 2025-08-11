<?php

namespace App\Console\Commands;

use App\Http\Repositories\EncostRepository;
use App\Notifications\Encost\EncostRequestErrorNotification;
use App\Notifications\Encost\EncostRequestHasChangesNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class EncostMachinesHierarchyCommand extends Command
{

    protected $signature = 'app:encost-machines-hierarchy-command';
    protected $description = 'Проверка структуры машин и их типов';

    public function __construct(public EncostRepository $repository)
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $filePath = 'responses/';
            $fileName = 'encost_hierarchy.json';

            // Получаем новые данные из API
            $response = $this->repository->getEncostHierarchyMachinesList();
            if ($response->getStatusCode() !== 200) {
                Notification::route('telegram', 'TELEGRAM_CHAT_ID')
                    ->notify(new EncostRequestErrorNotification("Ошибка при получении иерархии машин, код " . $response->getStatusCode()));
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
                        ->notify(new EncostRequestHasChangesNotification("Структура иерархии машин была изменена!"));
                }
                Storage::disk('local')->put($filePath . 'old_'. $fileName, json_encode($oldData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            }

            // Сохраняем новые данные
            Storage::disk('local')->put($filePath . $fileName, json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        } catch (\Exception $e) {
            Log::error('Ошибка: ' . $e->getMessage());
            Notification::route('telegram', 'TELEGRAM_CHAT_ID')
                ->notify(new EncostRequestErrorNotification('Ошибка при получении и сравнении иерархии машин'));
        }
    }
}
