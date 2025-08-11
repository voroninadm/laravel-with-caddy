<?php

namespace AppServices;

use App\Models\Elog\Cutting;
use App\Models\Elog\Extrusion;
use App\Models\Elog\Lamination;
use App\Models\Elog\Printing;
use App\Models\Elog\Recycling;
use App\Models\Elog\Welding;


class DashboardReportService
{
    private $yesterdayStart;
    private $todayEnd;

    public function getDailyReport($yesterdayStart, $todayEnd): array
    {
        $this->yesterdayStart = $yesterdayStart;
        $this->todayEnd = $todayEnd;

        $printing_mass = $this->getDailyData(Printing::class, 'workout_mass');
        $printing_length = $this->getDailyData(Printing::class, 'workout_length');

        $lamination_mass = $this->getDailyData(Lamination::class, 'workout_mass');
        $lamination_length = $this->getDailyData(Lamination::class, 'workout_length');

        $welding = $this->getDailyData(Welding::class, 'workout_count');

        $cutting_mass = $this->getDailyData(Cutting::class, 'workout_mass');
        $cutting_length = $this->getDailyData(Cutting::class, 'raw_meters');

        $extrusion_mass = $this->getDailyData(Extrusion::class, 'workout_mass');
        $extrusion_length = $this->getDailyData(Extrusion::class, 'workout_length');

        $recycling = $this->getDailyData(Recycling::class, 'workout_mass');

        return [
            'date_start' => $this->yesterdayStart,
            'date_finish' => $this->todayEnd,
            'printing_mass' => $printing_mass,
            'printing_length' => $printing_length,
            'lamination_mass' => $lamination_mass,
            'lamination_length' => $lamination_length,
            'welding' => $welding,
            'cutting_mass' => $cutting_mass,
            'cutting_length' => $cutting_length,
            'extrusion_mass' => $extrusion_mass,
            'extrusion_length' => $extrusion_length,
            'recycling' => $recycling
        ];
    }

    private function getDailyData($machineClass, $columnName)
    {
        $dailyData = $machineClass::where('work_finish', '>=', $this->yesterdayStart)
            ->where('work_finish', '<=', $this->todayEnd)
            ->sum($columnName);

        return round($dailyData, 2);
    }
}
