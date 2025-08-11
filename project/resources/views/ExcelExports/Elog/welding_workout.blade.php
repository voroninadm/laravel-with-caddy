<?php

function calculateTechOpps($task) {
    $calc =  (
        $task['change_teflon'] +
        $task['reconfig'] +
        $task['calibrating']
    );

    return $calc !== 0 ? number_format($calc, 2) : '';
}

function calculateIdles($task)
{
    $calc = (
        $task['no_human'] +
        $task['no_work'] +
        $task['no_raw'] +
        $task['electro'] +
        $task['mechanical'] +
        $task['tech_service']
    );

    return $calc !== 0 ? number_format($calc, 2) : '';
}

function calculateWorkout($task)
{
    $calc = (
        $task['workout_count'] +
        $task['workout_otk']
    );

    return $calc !== 0 ? $calc : '';
}

?>
    <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчет по выработке сварки</title>
</head>
<body>

<p>Отчет по выработке сварки c {{ $work_start }} по {{ $work_finish }}</p>
<br>
<table>
    <thead>
    <tr>
        <th>Машина</th>
        <th>Дата смены</th>
        <th>Cмена</th>
        <th>Простой всей смены</th>
        <th>Операторы</th>
        <th>Приёмщик</th>
        <th>Номер карты</th>
        <th>Материал</th>
        <th>Толщина</th>
        <th>Материал-2</th>
        <th>Толщина-2</th>
        <th>Материал-3</th>
        <th>Толщина-3</th>
        <th>Ширина</th>
        <th>Длина</th>
        <th>Тип дна</th>
        <th>Карман</th>
        <th>Ручка</th>
        <th>Кромка</th>
        <th>Перфорация</th>
        <th>Zip lock</th>
        <th>Выработка (тыс.шт)</th>
        <th>Тех операции</th>
        <th>Простои</th>
        <th>Примечание</th>
    </tr>
    </thead>
    @foreach($data as $order)
        <tr>
            <td title="Машина">{{ $order['machine_id'] }}</td>
            <td title="Дата смены">{{ date('d.m.Y', strtotime($order['work_date'])) }}</td>
            <td title="Смена">{{ $order['work_shift'] }}</td>
            <td title="простой всей смены">{{ $order['is_idle'] ? 'да' : '' }}</td>
            <td title="Операторы">{{ implode(" ", array_filter([$order['operator1_id'], $order['operator2_id'], $order['operator3_id'], $order['operator4_id'], $order['operator5_id']])) }}</td>
            <td title="Приёмщик">{{ $order['acceptor_id'] }}</td>
            <td title="Номер карты">{{ $order['tkn'] }}</td>
            <td title="Материал">{{ $order['mat1_id'] }}</td>
            <td title="Толщина">{{ $order['thickness1'] }}</td>
            <td title="Материал-2">{{ $order['mat2_id'] }}</td>
            <td title="Толщина-2">{{ $order['thickness2'] }}</td>
            <td title="Материал-3">{{ $order['mat3_id'] }}</td>
            <td title="Толщина-3">{{ $order['thickness3'] }}</td>
            <td title="Ширина">{{ $order['product_width'] }}</td>
            <td title="Длина">{{ $order['length'] }}</td>
            <td title="Тип дна">{{ $order['bottom_type'] }}</td>
            <td title="Карман">{{ $order['is_pocket'] ? "Да" : ''}}</td>
            <td title="Ручка">{{ $order['is_handle'] ? "Да" : ''}}</td>
            <td title="Кромка">{{ $order['is_edge'] ? "Да" : '' }}</td>
            <td title="Перфорация">{{ $order['is_perforation'] ? "Да" : '' }}</td>
            <td title="Zip lock">{{ $order['is_ziplock'] ? "Да" : '' }}</td>
            <td title="Выработка тыс.шт">{{ calculateWorkout($order) }}</td>
            <td title="Тех операции">{{ calculateTechOpps($order) }}</td>
            <td title="Простои">{{ calculateIdles($order) }}</td>
            <td title="Примечание">{{ $order['notes'] }}</td>
        </tr>
    @endforeach
</table>

</body>
</html>
