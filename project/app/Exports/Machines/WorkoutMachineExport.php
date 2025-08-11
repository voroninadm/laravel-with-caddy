<?php

namespace App\Exports\Machines;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;



class WorkoutMachineExport implements FromView, WithStyles, ShouldAutoSize
{    use Exportable;

    private $data;
    private $work_start;
    private $work_finish;
    private $type;

    public function __construct($data, $work_start, $work_finish, $type)
    {
        $this->data = $data;
        $this->work_start = $work_start;
        $this->work_finish = $work_finish;
        $this->type = $type;
    }

    public function view(): View
    {
        return view("ExcelExports/Elog/{$this->type}_workout", [
            'data' => $this->data,
            'work_start' => $this->work_start,
            'work_finish' => $this->work_finish
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Стили для строк
            1    => ['font' => ['italic' => true, 'bold' => true],
            ],
            3    => ['font' => ['italic' => true, 'bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFCCCCCC']], // Gray background
            ],

            'A4:A' . ($sheet->getHighestRow()) => [
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ebebeb']], // Gray background
            ],

            // Контент по центру для всех колонок кроме первой, с названиями машин
            'B1:' . $sheet->getHighestColumn() . $sheet->getHighestRow() => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],

            ],
            'A3:' . $sheet->getHighestColumn() . $sheet->getHighestRow() => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ],
        ];
    }

}
