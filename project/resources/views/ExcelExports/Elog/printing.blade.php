<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчет печати</title>
</head>
<body>

<p>Отчет печати c {{ $work_start }} по {{ $work_finish }}</p>
<br>
<table>
    <thead>
    <tr>
        <th>Машина</th>
        <th>Дата смены</th>
        <th>Cмена</th>
        <th>Простой всей смены</th>
        <th>Мастер</th>
        <th>Операторы</th>
        <th>Помощник</th>
        <th>Номер карты</th>
        <th>Начало работ</th>
        <th>Окончание работ</th>
        <th>Плановое время</th>
        <th>Фактическое время</th>
        <th>Время выпуска</th>
        <th>Заказчик</th>
        <th>Заказ</th>
        <th>Тираж</th>
        <th>Материал</th>
        <th>План материала</th>
        <th>Факт материала</th>
        <th>Ширина</th>
        <th>Толщина</th>
        <th>Краски</th>
        <th>Выработка кг</th>
        <th>Выработка м.пог</th>
        <th>Выработка м<sup>2</sup></th>
        <th>Выработка ОТК</th>
        <th>Разница тиража</th>
        <th>Скорость печати</th>

        <th>Отходы план</th>
        <th>Отходы печать</th>
        <th>Отходы сырье</th>
        <th>Отходы итог</th>
        <th>Приправка кг</th>
        <th>Приправка час</th>
        <th>Коррекция PN</th>
        <th>Коррекция CMYK</th>
        <th>Простой электрика</th>
        <th>Простой механика</th>
        <th>Переклейка форм</th>
        <th>Замена расходников</th>
        <th>Техническое обслуживание</th>
        <th>Отсутствие людей</th>
        <th>Отсутствие работы</th>
        <th>Отсутствие сырья</th>

        <th>Краткосрочный простой</th>
        <th>Простой без причины</th>
        <th>Общий простой</th>

        <th>Примечание</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $order)
        <tr>
            <td title="Машина">{{ $order['machine_id'] }}</td>
            <td title="Дата смены">{{ date('d.m.Y', strtotime($order['work_date'])) }}</td>
            <td title="Cмена">{{ $order['work_shift'] }}</td>
            <td title="вся смена - простой">{{ $order['is_idle'] ? 'да' : '' }}</td>
            <td title="Мастер">{{ $order['master_id'] }}</td>
            <td title="Операторы">{{ implode(" ", array_filter([$order['operator1_id'], $order['operator2_id'], $order['operator3_id']])) }}</td>
            <td title="Помощник">{{ $order['operator_helper'] }}</td>
            <td title="Номер карты">{{ $order['tkn'] }}</td>
            <td title="Начало работ">{{ $order['work_start'] ? date('d.m.Y h:m', strtotime($order['work_start'])) : '' }}</td>
            <td title="Окончание работ">{{ $order['work_finish'] ? date('d.m.Y h:m', strtotime($order['work_finish'])) : '' }}</td>
            <td title="Плановое время">{{ $order['work_plan'] }}</td>
            <td title="Фактическое время">{{ $order['work_fact'] }}</td>
            <td title="Время выпуска">{{ $order['work_productive'] }}</td>
            <td title="Заказчик">{{ $order['customer'] }}</td>
            <td title="Заказ">{{ $order['print_title'] }}</td>
            <td title="Тираж">{{ $order['circulation'] }}</td>
            <td title="Материал">{{ $order['mat1_id'] }}</td>
            <td title="План материала">{{ $order['mat1count_plan'] }}</td>
            <td title="Факт материала">{{ $order['mat1count'] }}</td>
            <td title="Ширина">{{ $order['width1'] }}</td>
            <td title="Толщина">{{ $order['thickness1'] }}</td>
            <td title="Краски">{{ $order['colors'] }}</td>
            <td title="Выработка кг">{{ $order['workout_mass'] }}</td>
            <td title="Выработка м.пог">{{ $order['workout_length'] }}</td>
            <td title="Выработка м2">{{ $order['workout_m2'] }}</td>
            <td title="Выработка ОТК">{{ $order['otk_mass'] }}</td>
            <td title="Разница тиража">{{ $order['diff_circulation'] }}</td>
            <td title="Скорость печати">{{ $order['speed'] }}</td>

            <td title="Отходы план">{{ $order['waste_plan'] }}</td>
            <td title="Отходы печать">{{ $order['waste_print'] }}</td>
            <td title="Отходы сырье">{{ $order['waste_raw'] }}</td>
            <td title="Отходы итог">{{ $order['waste_sum'] }}</td>

            <td title="Приправка кг">{{ $order['prepare_mass'] }}</td>
            <td title="Приправка час">{{ $order['prepare_hours'] }}</td>
            <td title="Коррекция PN">{{ $order['correction_PN'] }}</td>
            <td title="Коррекция CMYK">{{ $order['correction_CMYK'] }}</td>
            <td title="Простой электрика">{{ $order['electro'] }}</td>
            <td title="Простой механика">{{ $order['mechanical'] }}</td>
            <td title="Переклейка форм">{{ $order['form_glue'] }}</td>

            <td title="Замена расходников">{{ $order['service_replacement'] }}</td>
            <td title="Техобслуживание">{{ $order['tech_service'] }}</td>
            <td title="Отсутствие людей">{{ $order['no_human'] }}</td>
            <td title="Отсутствие работы">{{ $order['no_work'] }}</td>
            <td title="Отсутствие сырья">{{ $order['no_raw'] }}</td>

            <td title="Краткосрочный простой">{{ $order['short_downtime'] }}</td>
            <td title="Простой без причины">{{ $order['no_reason_downtime'] }}</td>
            <td title="Общий простой">{{ $order['total_downtime'] }}</td>

            <td title="Примечание">{{ $order['notes'] }}</td>
        </tr>
    @endforeach
    <tr>
        <td title="Машина"></td>
        <td title="Дата смены"></td>
        <td title="Смена"></td>
        <td title="вся смена - простой"></td>
        <td title="Мастер"></td>
        <td title="Операторы"></td>
        <td title="Помощник"></td>
        <td title="Номер карты">карт: {{ countUniqueTkn($data) }}</td>
        <td title="Плановое время"></td>
        <td title="Начало работ"></td>
        <td title="Окончание работ"></td>
        <td title="Фактическое время"></td>
        <td title="Время выпуска">{{ summarize($data, 'work_productive') }}</td>
        <td title="Заказчик"></td>
        <td title="Заказ"></td>
        <td title="Тираж"></td>
        <td title="Материал"></td>
        <td title="План материала"></td>
        <td title="Факт материала">{{ summarize($data, 'mat1count') }}</td>
        <td title="Ширина">{{ averaging($data, 'width1') }}</td>
        <td title="Толщина">{{ averaging($data, 'thickness1') }}</td>
        <td title="Краски">{{ averaging($data, 'colors') }}</td>
        <td title="Выработка кг2">{{ summarize($data, 'workout_mass') }}</td>
        <td title="Выработка м.пог">{{ summarize($data, 'workout_length') }}</td>
        <td title="Выработка м2">{{ summarize($data, 'workout_m2') }}</td>
        <td title="Выработка ОТК">{{ summarize($data, 'otk_mass') }}</td>
        <td title="Разница тиража"></td>
        <td title="Скорость печати">{{ averaging($data, 'speed') }}</td>

        <td title="Отходы план"></td>
        <td title="Отходы печать">{{ summarize($data, 'waste_print') }}</td>
        <td title="Отходы сырье">{{ summarize($data, 'waste_raw') }}</td>
        <td title="Отходы итог">{{ summarize($data, 'waste_sum') }}</td>

        <td title="Приправка кг">{{ summarize($data, 'prepare_mass') }}</td>
        <td title="Приправка час">{{ summarize($data, 'prepare_hours') }}</td>
        <td title="Коррекция PN">{{ summarize($data, 'correction_PN') }}</td>
        <td title="Коррекция CMYK">{{ summarize($data, 'correction_CMYK') }}</td>
        <td title="Простой электрика">{{ summarize($data, 'electro') }}</td>
        <td title="Простой механика">{{ summarize($data, 'mechanical') }}</td>
        <td title="Переклейка форм">{{ summarize($data, 'form_glue') }}</td>
        <td title="Замена расходников">{{ summarize($data, 'service_replacement') }}</td>
        <td title="Техническое обслуживание">{{ summarize($data, 'tech_service') }}</td>
        <td title="Отсутствие людей">{{ summarize($data, 'no_human') }}</td>
        <td title="Отсутствие работы">{{ summarize($data, 'no_work') }}</td>
        <td title="Отсутствие сырья">{{ summarize($data, 'no_raw') }}</td>

        <td title="Краткосрочный простой">{{ summarize($data, 'short_downtime') }}</td>
        <td title="Простой без причины">{{ summarize($data, 'no_reason_downtime') }}</td>
        <td title="Общий простой">{{ summarize($data, 'total_downtime') }}</td>
        <td title="Примечание"></td>
    </tr>
    </tbody>
</table>

</body>
</html>
