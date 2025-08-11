<?php

function calculateTechOpps($task) {
    $calc =  (
        $task['tech_clean'] +
        $task['change_glue'] +
        $task['electro'] +
        $task['mechanical'] +
        $task['tech_service']
    );

    return $calc !== 0 ? number_format($calc, 2) : '';
}

?>
    <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчет по выработке ламинации</title>
</head>
<body>

<p>Отчет по выработке ламинации c {{ $work_start }} по {{ $work_finish }}</p>
<br>
<table>
    <thead>
    <tr>
        <th>Машина</th>
        <th>Дата смены</th>
        <th>Cмена</th>
        <th>Простой всей смены</th>
        <th>Операторы</th>
        <th>Номер карты</th>
        <th>Материал-1</th>
        <th>Материал-2</th>
        <th>Покрытие</th>
        <th>Выработка м<sup>2</sup></th>
        <th>Приправка час</th>
        <th>Тех операции</th>
        <th>Примечание</th>
    </tr>
    </thead>
    @foreach($data as $order)
        <tr>
            <td title="Машина">{{ $order['machine_id'] }}</td>
            <td title="Дата смены">{{ date('d.m.Y', strtotime($order['work_date'])) }}</td>
            <td title="Смена">{{ $order['work_shift'] }}</td>
            <td title="Вся смена - простой">{{ $order['is_idle'] ? 'да' : '' }}</td>
            <td title="Операторы">{{ implode(" ", array_filter([$order['operator1_id'], $order['operator2_id'], $order['operator3_id']])) }}</td>
            <td title="Номер карты">{{ $order['tkn'] }}</td>
            <td title="Материал">{{ $order['mat1_id'] }}</td>
            <td title="Материал">{{ $order['mat2_id'] }}</td>
            <td title="Покрытие">{{ $order['mat3_id'] }}</td>
            <td title="Выработка м2">{{ $order['workout_m2'] }}</td>
            <td title="Приправка час">{{ $order['prepare_hours'] }}</td>
            <td title="Тех операции">{{ calculateTechOpps($order) }}</td>
            <td title="Примечание">{{ $order['notes'] }}</td>
        </tr>
    @endforeach
</table>

</body>
</html>
