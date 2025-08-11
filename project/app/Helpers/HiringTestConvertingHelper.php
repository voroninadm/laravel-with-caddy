<?php

namespace App\Helpers;

class HiringTestConvertingHelper
{
    private array $converter;
    private array $positions_table;

    public function __construct()
    {
        $this->converter = config('Cattell.Cattell_results');
        $this->positions_table = config('Cattell.Cattell_table_positions');
    }

    /** Конвертер сырых результатов тестирования в стены
     * @param array $raw_test_results
     * @return array
     */
    public function convertRawPointsToSten(array $raw_test_results): array
    {
        $results_in_sten = [];
        foreach ($raw_test_results as $factor => $raw_point) {
            if ($factor === 'MD') {
                $results_in_sten['MD'] = $raw_point;
                continue;
            }
            $results_in_sten[$factor] = $this->converter[$factor]['points_to_sten'][$raw_point];
        }
        $results_in_sten['F1'] = $this->calcF1Factor($raw_test_results);
        $results_in_sten['F2'] = $this->calcF2Factor($raw_test_results);
        $results_in_sten['F3'] = $this->calcF3Factor($raw_test_results);
        $results_in_sten['F4'] = $this->calcF4Factor($raw_test_results);

        return $results_in_sten;
    }

    /** Конвертер стенов в позиции точек для графика
     * @param array $results_in_sten
     * @return array
     */
    public function convertStenToChartPositions(array $results_in_sten): array
    {
        $positions = [];
        foreach ($results_in_sten as $factor => $result) {
            if ($factor == 'MD') {
                $positions['MD'] = $result;
                continue;
            }
            $positions[$factor] = $this->positions_table[$result];
        }
        return $positions;
    }

    private function calcF1Factor($sten_results): float|int
    {
        $F1 = ((38 + 2 * $sten_results['L'] + 3 * $sten_results['O'] + 4 * $sten_results['Q4'])
                - ($sten_results['C'] + $sten_results['H'] + $sten_results['Q3'])) / 10;
        return floor($F1);
    }

    private function calcF2Factor($sten_results): float|int
    {
        $F2 = ((2 * $sten_results['A'] + 3 * $sten_results['E'] + 4 * $sten_results['F'] + 5 * $sten_results['H'])
                - (2 * $sten_results['Q2'] + 11)) / 10;
        return floor($F2);
    }

    private function calcF3Factor($sten_results): float|int
    {
        $F3 = ((77 + 2 * $sten_results['C'] + 2 * $sten_results['E'] + 2 * $sten_results['F'] + 2 * $sten_results['N'])
                - (4 * $sten_results['A'] + 6 * $sten_results['I'] + 2 * $sten_results['M'])) / 10;
        return ceil($F3);
    }

    private function calcF4Factor($sten_results): float|int
    {
        $F4 = ((4 * $sten_results['E'] + 3 * $sten_results['M'] + 4 * $sten_results['Q1'] + 4 * $sten_results['Q2'])
                - (3 * $sten_results['A'] + 2 * $sten_results['G'])) / 10;
        return round($F4);
    }
}
