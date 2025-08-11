<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SpoolcutterWorkoutExport implements FromView, ShouldAutoSize, WithStyles
{
    use Exportable;

    private $work_start;
    private $work_finish;
    private $spoolcutter;
    private $spoolcutter_workout;


    public function __construct($work_start, $work_finish, $spoolcutter, $spoolcutter_workout)
    {
        $this->work_start = $work_start;
        $this->work_finish = $work_finish;
        $this->spoolcutter = $spoolcutter;
        $this->spoolcutter_workout = $spoolcutter_workout;
    }

    public function view(): View
    {
        return view('ExcelExports/spoolcutter_workout', [
            'work_start' => $this->work_start,
            'work_finish' => $this->work_finish,
            'spoolcutter' => $this->spoolcutter,
            'spoolcutter_workout' => $this->spoolcutter_workout
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Стили для строк
            1 => ['font' => ['italic' => true, 'bold' => true],
            ],
            4 => ['font' => ['italic' => true, 'bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFCCCCCC']], // Gray background
            ],

            // Контент по центру для всех колонок
            'A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow() => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

}
