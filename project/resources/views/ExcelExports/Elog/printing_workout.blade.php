<?php
function calculateTechOpps($task)
{
    $calc = (
        $task['correction_PN'] +
        $task['correction_CMYK'] +
        $task['electro'] +
        $task['mechanical'] +
        $task['tech_service'] +
        $task['form_glue'] +
        $task['service_replacement']
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
    <title>Отчет по выработке печати</title>
</head>
<body>

<p>Отчет по выработке печати c {{ $work_start }} по {{ $work_finish }}</p>
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
        <th>Материал</th>
        <th>Ширина</th>
        <th>Толщина</th>
        <th>Краски</th>
        <th>Выработка кг</th>
        <th>Выработка м.пог</th>
        <th>Выработка м<sup>2</sup></th>
        <th>Приправка час</th>
        <th>Простой без причины</th>
        <th>Тех операции</th>
        <th>Примечание</th>
    </tr>
    </thead>
    @foreach($data as $order)
        <tr>
            <td title="Машина">{{ $order->machine_id }}</td>
            <td title="Дата смены">{{ date('d.m.Y', strtotime($order['work_date'])) }}</td>
            <td title="Смена">{{ $order->work_shift }}</td>
            <td title="Вся смена - простой">{{ $order['is_idle'] ? 'да' : '' }}</td>
            <td title="Операторы">{{ implode(" ", array_filter([$order['operator1_id'], $order['operator2_id'], $order['operator3_id']])) }}</td>
            <td title="Номер карты">{{ $order->tkn }}</td>
            <td title="Материал">{{ $order->mat1_id }}</td>
            <td title="Ширина">{{ $order->width1 }}</td>
            <td title="Толщина">{{ $order->thickness1 }}</td>
            <td title="Краски">{{ $order->colors }}</td>
            <td title="Выработка кг">{{ $order->workout_mass }}</td>
            <td title="Выработка м.пог">{{ $order->workout_length }}</td>
            <td title="Выработка м2">{{ $order->workout_m2 }}</td>
            <td title="Приправка час">{{ $order->prepare_hours }}</td>
            <td title="Простой без причины">{{ $order->no_reason_downtime }}</td>
            <td title="Тех операции">{{ calculateTechOpps($order) }}</td>
            <td title="Примечание">{{ $order->notes }}</td>
        </tr>
    @endforeach
</table>

</body>
</html>
