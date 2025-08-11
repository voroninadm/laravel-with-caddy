<?php

function calculateTechOpps($task) {
    $calc =  (
        $task['electro'] +
        $task['mechanical'] +
        $task['tech_service'] +
        $task['knifes_barbell']
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
    <title>Отчет по выработке резки</title>
</head>
<body>

<p>Отчет по выработке резки c {{ $work_start }} по {{ $work_finish }}</p>
<br>
<table>
    <thead>
    <tr>
        <th>Машина</th>
        <th>Дата смены</th>
        <th>Cмена</th>
        <th>Простой всей смены</th>
        <th>Оператор</th>
        <th>Упаковщик</th>
        <th>Номер карты</th>
        <th>Заказчик</th>
        <th>Материал-1</th>
        <th>Материал-2</th>
        <th>Материал-3</th>
        <th>Количество ручьев</th>
        <th>Выработка пог.м</th>
        <th>Перестройка</th>
        <th>Тех операции</th>
        <th>Примечание</th>
    </tr>
    </thead>
    @foreach($data as $order)
        <tr>
            <td title="Машина">{{ $order['machine_id'] }}</td>
            <td title="Дата смены">{{ date('d.m.Y', strtotime($order['work_date'])) }}</td>
            <td title="Смена">{{ $order['work_shift'] }}</td>
            <td title="простой всей смены">{{ $order['is_idle'] ? 'да' : '' }}</td>
            <td title="Оператор">{{ $order['operator_id'] }}</td>
            <td title="Упаковщик">{{ $order['packer_id'] }}</td>
            <td title="Номер карты">{{ $order['tkn'] }}</td>
            <td title="Заказчик">{{ $order['customer'] }}</td>
            <td title="Материал1">{{ $order['mat1_id'] }}</td>
            <td title="Материал2">{{ $order['mat2_id'] }}</td>
            <td title="Материал3">{{ $order['mat3_id'] }}</td>
            <td title="Количество ручьев">{{ $order['streams'] }}</td>
            <td title="Выработка пог.м">{{ $order['workout_length'] }}</td>
            <td title="Перестройка">{{ $order['reconfig'] }}</td>
            <td title="Тех операции">{{calculateTechOpps($order)}}</td>
            <td title="Примечание">{{ $order['notes'] }}</td>
        </tr>
    @endforeach
</table>

</body>
</html>
