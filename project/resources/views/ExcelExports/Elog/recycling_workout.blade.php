<?php

function calculateTechOpps($task)
{
    $calc = (
        $task['change_net'] +
        $task['change_knifes'] +
        $task['change_cutter'] +
        $task['tech_clean']
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
    <title>Отчет по выработке переработки</title>
</head>
<body>

<p>Отчет по выработке переработки c {{ $work_start }} по {{ $work_finish }}</p>
<br>
<table>
    <thead>
    <tr>
        <th>Машина</th>
        <th>Дата смены</th>
        <th>Cмена</th>
        <th>Машинист</th>
        <th>Подсобный рабочий</th>
        <th>Номенклатура гранулят</th>
        <th>Выработка гранулят</th>
        <th>Номенклатура литники</th>
        <th>Выработка литники</th>
        <th>Приправка, час</th>
        <th>Тех операции</th>
        <th>Мойка</th>
        <th>Ремонт</th>
        <th>Примечание</th>
    </tr>
    </thead>
    @foreach($data as $order)
        <tr>
            <td title="Машина">{{  $order['machine_id'] }}</td>
            <td title="Дата смены">{{ date('d.m.Y', strtotime($order['work_date'])) }}</td>
            <td title="Смена">{{  $order['work_shift'] }}</td>
            <td title="Машинист">{{  $order['machinist_id'] }}</td>
            <td title="Подсобный рабочий">{{  $order['helper_id'] }}</td>
            <td title="Номенклатура гранулят">{{  $order['nomenclature'] }}</td>
            <td title="Выработка гранулят">{{  $order['workout_mass'] }}</td>
            <td title="Номенклатура литники">{{  $order['ingots_type'] }}</td>
            <td title="Выработка литники">{{  $order['waste_mass'] }}</td>
            <td title="Приправка час">{{  $order['prepare_hours'] }}</td>
            <td title="Тех.операции">{{ calculateTechOpps($order)}}</td>
            <td title="Мойка">{{ $order['clean_machine'] }}</td>
            <td title="Ремонт">{{ calculateRepair($order)}}</td>
            <td title="Примечание">{{  $order['notes'] }}</td>
        </tr>
    @endforeach
</table>

</body>
</html>
