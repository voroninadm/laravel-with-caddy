<?php

function calculateTechOpps($task)
{
    $calc = (
        $task['change_net'] +
        $task['change_raw'] +
        $task['change_order'] +
        $task['clean_machine']
    );

    return $calc !== 0 ? number_format($calc, 2) : '';
}

function calculateRepair($task)
{
    $calc = (
        $task['electro'] +
        $task['mechanical'] +
        $task['electronics'] +
        $task['tech_service']
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
    <title>Отчет по выработке экструзия</title>
</head>
<body>

<p>Отчет по выработке экструзии c {{ $work_start }} по {{ $work_finish }}</p>
<br>
<table>
    <thead>
    <tr>
        <th>Машина</th>
        <th>Дата смены</th>
        <th>Cмена</th>
        <th>Машинист-1</th>
        <th>Машинист-2</th>
        <th>Машинист-3</th>
        <th>Номер карты</th>
        <th>Номер партии</th>
        <th>Заказчик</th>
        <th>Ширина</th>
        <th>Толщина</th>
        <th>Выработка кг</th>
        <th>Выработка пог.м</th>
        <th>Выработка м<sup>2</sup></th>
        <th>Приправка, час</th>
        <th>Тех операции</th>
        <th>Ремонт</th>
        <th>Примечание</th>
    </tr>
    </thead>
    @foreach($data as $order)
        <tr>
            <td title="Машина">{{  $order['machine_id'] }}</td>
            <td title="Дата смены">{{ date('d.m.Y', strtotime($order['work_date'])) }}</td>
            <td title="Смена">{{  $order['work_shift'] }}</td>
            <td title="Машинист-1">{{  $order['machinist1_id'] }}</td>
            <td title="Машинист-2">{{  $order['machinist2_id'] }}</td>
            <td title="Машинист-3">{{  $order['machinist3_id'] }}</td>
            <td title="номер карты">{{  $order['tkn'] }}</td>
            <td title="номер партии">{{  $order['part_number'] }}</td>
            <td title="Заказчик">{{  $order['customer'] }}</td>
            <td title="Ширина">{{  $order['width'] }}</td>
            <td title="Толщина">{{  $order['thickness'] }}</td>
            <td title="Выработка, кг">{{  $order['workout_mass'] }}</td>
            <td title="Выработка, пог. м.">{{  $order['workout_length'] }}</td>
            <td title="Выработка м2">{{  $order['workout_m2'] }}</td>
            <td title="Приправка час">{{  $order['prepare_hours'] }}</td>
            <td title="Тех.операции">{{ calculateTechOpps($order)}}</td>
            <td title="Ремонт">{{ calculateRepair($order)}}</td>
            <td title="Примечание">{{  $order['notes'] }}</td>
        </tr>
    @endforeach
</table>

</body>
</html>
