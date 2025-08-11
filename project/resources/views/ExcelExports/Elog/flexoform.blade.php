<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчет монтаж флексоформ</title>
</head>
<body>

<p>Отчет по монтажу флексоформ с {{ $work_start }} по {{ $work_finish }}</p>
<br>
<table>
    <thead>
    <tr>
        <th>Машина</th>
        <th>Печатная машина</th>
        <th>Дата смены</th>
        <th>Cмена</th>
        <th>Мастер</th>
        <th>Сборщик</th>
        <th>Номер карты</th>
        <th>Начало работ</th>
        <th>Окончание работ</th>
        <th>Фактическое время</th>
        <th>Заказчик</th>
        <th>Заказ</th>
        <th>Номер дизайна</th>
        <th>Количество ручьев</th>
        <th>Количество красок</th>
        <th>Диаметр сливс</th>
        <th>Количество сливс, факт</th>
        <th>Новые формы</th>
        <th>Чистка анилоксов, шт</th>
        <th>Краски и липкая лента</th>
        <th>Расстояние между ручьями проверено</th>
        <th>Проверка монтажных точек проведена</th>
        <th>Примечание</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $order)
        <tr>
            <td title="Машина">{{ $order['machine_id'] }}</td>
            <td title="Машина печати">{{ $order['print_machine_id'] }}</td>
            <td title="Дата смены">{{ date('d.m.Y', strtotime($order['work_date'])) }}</td>
            <td title="Смена">{{ $order['work_shift'] }}</td>
            <td title="Мастер">{{ $order['master_id'] }}</td>
            <td title="Сборщик">{{ $order['collector_id'] }}</td>
            <td title="Номер карты">{{ $order['tkn'] }}</td>
            <td title="Начало работ">{{ $order['work_start'] ? date('d.m.Y h:m', strtotime($order['work_start'])) : '' }}</td>
            <td title="Окончание работ">{{ $order['work_start'] ? date('d.m.Y h:m', strtotime($order['work_finish'])) : '' }}</td>
            <td title="Фактическое время">{{ $order['work_fact'] }}</td>
            <td title="Заказчик">{{ $order['customer'] }}</td>
            <td title="Заказ">{{ $order['print_title'] }}</td>
            <td title="Номер дизайна">{{ $order['design_number'] }}</td>
            <td title="Количество ручьев">{{ $order['streams'] }}</td>
            <td title="Количество красок">{{ $order['paints_count'] }}</td>
            <td title="Диаметр сливс">{{ $order['sleeves_diameter'] }}</td>
            <td title="Количество сливс, факт">{{ $order['sleeves_fact'] }}</td>
            <td title="Новые формы">{{ $order['new_forms'] }}</td>
            <td title="Чистка анилоксов, шт">{{ $order['aniloks'] }}</td>
            <td title="Краски и липкая лента">
                @foreach($order->paints_and_sticky as $paints_data)
                    <p> - {{ $paints_data['paints'] }} на липкой {{ $paints_data['sticky_brand'] }},
                        {{ $paints_data['sticky_name'] }}({{ $paints_data['sticky_thickness'] }})
                    </p>
                @endforeach
            </td>
            <td title="Расстояние между ручьями проверено">{{ $order['is_streams_distance_ok'] ? "Да" : 'Нет' }}</td>
            <td title="Проверка монтажных точек проведена">{{ $order['is_mounting_dots_ok'] ? "Да" : 'Нет' }}</td>
            <td title="Примечание">{{ $order['notes'] }}</td>
        </tr>
    @endforeach
    <tr>
        <td title="Машина"></td>
        <td title="Машина печати"></td>
        <td title="Дата смены"></td>
        <td title="Смена"></td>
        <td title="Мастер"></td>
        <td title="Сборщик"></td>
        <td title="Номер карты">карт: {{ countUniqueTkn($data) }}</td>
        <td title="Начало работ"></td>
        <td title="Окончание работ"></td>
        <td title="Фактическое время"></td>
        <td title="Заказчик"></td>
        <td title="Заказ"></td>
        <td title="Номер дизайна"></td>
        <td title="Количество ручьев"></td>
        <td title="Количество красок"></td>
        <td title="Диаметр сливс"></td>
        <td title="Количество сливс, факт">{{ summarize($data, 'sleeves_fact') }}</td>
        <td title="Краски"></td>
        <td title="Новые формы"></td>
        <td title="Чистка анилоксов, шт">{{ summarize($data, 'aniloks') }}</td>
        <td title="Производитель липкой ленты"></td>
        <td title="Марка липкой ленты"></td>
        <td title="Толщина липкой ленты"></td>
        <td title="Расстояние между ручьями проверено"></td>
        <td title="Проверка монтажных точек проведена"></td>
        <td title="Примечание"></td>
    </tr>
    </tbody>
</table>

</body>
</html>
