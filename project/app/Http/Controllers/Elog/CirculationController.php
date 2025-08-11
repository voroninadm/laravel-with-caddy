<?php

namespace App\Http\Controllers\Elog;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\MachinesType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class CirculationController extends Controller
{
    /** Обращение к бд для расчета разницы тиража при создании задачи
     * @param MachinesType $mtype
     * @param Request $request
     * @return JsonResponse
     */
    public function getCirculations(MachinesType $mtype, Request $request): JsonResponse
    {
        $data = $request->validate([
            'type' => 'required|string|exists:machines_types,name',
            'tkn' => 'required',
        ]);

        $class = $mtype->matchClass($data['type']);
        $tasks = $class::where('tkn', $data['tkn'])->get();
        $tasksCount = count($tasks) ?? null;
        $fields_for_circulation = $class::FIELDS_FOR_REPORT['FIELDS_FOR_CIRCULATION'];
        $tasksCirculation =  0;
        foreach ($fields_for_circulation as $field) {
            $tasksCirculation += $tasks->sum($field);
        }

        return response()->json([
            'taskType' => $mtype->getTitleByName($data['type']),
            'tasksCount' => $tasksCount,
            'tasksCirculation' => $tasksCirculation
        ]);
    }
}
