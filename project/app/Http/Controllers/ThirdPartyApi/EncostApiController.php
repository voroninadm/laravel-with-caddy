<?php

namespace App\Http\Controllers\ThirdPartyApi;

use App\Http\Interfaces\EncostInterface;
use App\Models\Machine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EncostApiController
{
    public function __construct(protected EncostInterface $encostInterface)
    {
    }

    public function getIdlesData(Request $request)
    {
        $validated = $request->validate([
            'machine_id' => 'required|String',
            'work_start' => 'required|String',
            'work_finish' => 'required|String',
        ]);
        $encost_machine_id = Machine::where('title', $validated['machine_id'])->first()->encost_id;

        $work_start = Carbon::parse($validated['work_start'])->setTimezone('Europe/Moscow')->format('Y-m-d H:i:s');
        $work_finish = Carbon::parse($validated['work_finish'])->setTimezone('Europe/Moscow')->format('Y-m-d H:i:s');

        $data = null;
        $response = $this->encostInterface->getIdlesData($encost_machine_id, $work_start, $work_finish);

        if ($response->successful()) {
        $data = $response->json();
    }

        // обработка данных
        $report = [
            'Работа' => 0,
            'Не работа' => 0
        ];
        foreach ($data as $info) {
            if ($info['Статус'] === 'Работа')  {
                $report['Работа'] += $info['Длительность, ч'];
            }
            else {
                $report['Не работа'] += $info['Длительность, ч'];
                $reasonKey = $info['Причина'];
                if (!isset($report[$reasonKey])) {
                    $report[$reasonKey] = 0;
                }
                $report[$reasonKey] += $info['Длительность, ч'];
            }
        }
//фильтруем значения меньше минуты
        $report = array_filter($report, function($value) {
            return $value > 0.02;
        });
// округляем значение
        return array_map(function($value) {
            return round($value, 2);
        }, $report);
    }
}
