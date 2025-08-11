<?php


//===== Функции для выгруженных отчетов excel

/**
 * Считаем среднее значение по массиву и заданному параметру
 */
if (!function_exists('averaging')) {
    function averaging($tasks, $taskColumn): string
    {
        $total = 0;
        $count = 0;

        foreach ($tasks as $task) {
            if ($task[$taskColumn] !== null && $task[$taskColumn] !== 0 && $task[$taskColumn] !== '') {
                $total += floatval($task[$taskColumn]);
                $count++;
            }
        }

        return $count ? 'μ:' . number_format($total / $count, 2, ',', '') : '0';
    }
}

/**
 * Считаем сумму значения по массиву и заданному параметру
 */
if (!function_exists('summarize')) {
    function summarize($tasks, $taskColumn): string
    {
        $total = 0;

        foreach ($tasks as $task) {
            if ($task[$taskColumn] !== null && $task[$taskColumn] !== 0 && $task[$taskColumn] !== '') {
                $total += floatval($task[$taskColumn]);
            }
        }

        return $total ? 'Σ:' . number_format($total, 2, ',', '') : '';
    }
}

if (!function_exists('countUniqueTkn')) {
    function countUniqueTkn($tasks): int
    {
        $uniqueTkn = [];

        foreach ($tasks as $task) {
            if ($task['tkn'] !== '' && $task['tkn'] !== null && $task['tkn'] !== '0' && !in_array($task['tkn'], $uniqueTkn)) {
                $uniqueTkn[] = $task['tkn'];
            }
        }

        return count($uniqueTkn);
    }
}
