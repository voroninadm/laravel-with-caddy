<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчет сварка</title>
</head>
<body>

<p>Отчет сварки c {{ $work_start }} по {{ $work_finish }}</p>
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
        <th>Ученики</th>
        <th>Приёмщик</th>
        <th>Номер карты</th>
        <th>Начало работ</th>
        <th>Окончание работ</th>
        <th>Плановое время</th>
        <th>Фактическое время</th>
        <th>Заказчик</th>
        <th>Заказ</th>
        <th>Тираж</th>
        <th>Выработка факт</th>
        <th>Выработка ОТК</th>
        <th>Тип продукции</th>
        <th>Материал-1</th>
        <th>Толщина-1</th>
        <th>Материал-2</th>
        <th>Толщина-2</th>
        <th>Материал-3</th>
        <th>Толщина-3</th>
        <th>План материала</th>
        <th>Факт материала</th>
        <th>Тип дна</th>
        <th>Дно</th>
        <th>Ширина</th>
        <th>Длина</th>

        <th>Карман</th>
        <th>Ручка</th>
        <th>Кромка</th>
        <th>Перфорация</th>
        <th>Zip lock</th>

        <th>Отходы план</th>
        <th>Отходы печать</th>
        <th>Отходы кромка</th>
        <th>Отходы сварка</th>
        <th>Отходы итог</th>

        <th>Простой электрика</th>
        <th>Простой механика</th>
        <th>Скорость</th>
        <th>Перестройка</th>
        <th>Наладка</th>
        <th>Замена тефлона</th>
        <th>Тех обслуживание</th>
        <th>Отсутствие людей</th>
        <th>Отсутствие работы</th>
        <th>Отсутствие сырья</th>
        <th>Разница тиража</th>
        <th>Приправка принята</th>
        <th>Упаковка</th>
        <th>Примечание</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $order)
        <tr>
            <td title="Машина">{{ $order['machine_id'] }}</td>
            <td title="Дата смены">{{ date('d.m.Y', strtotime($order['work_date'])) }}</td>
            <td title="Смена">{{ $order['work_shift'] }}</td>
            <td title="простой всей смены">{{ $order['is_idle'] ? 'да' : '' }}</td>
            <td title="Мастер">{{ $order['master_id'] }}</td>
            <td title="Операторы">{{ implode(" ", array_filter([$order['operator1_id'], $order['operator2_id'], $order['operator3_id'], $order['operator4_id'], $order['operator5_id']], 'strlen')) }}</td>
            <td title="Ученики">{{ implode(" ", array_filter([$order['student1_id'], $order['student2_id']], 'strlen')) }}</td>
            <td title="Приемщик">{{ $order['acceptor_id'] }}</td>
            <td title="Номер карты">{{ $order['tkn'] }}</td>
            <td title="Начало работ">{{ $order['work_start'] ? date('d.m.Y h:m', strtotime($order['work_start'])) : '' }}</td>
            <td title="Окончание работ">{{ $order['work_start'] ? date('d.m.Y h:m', strtotime($order['work_finish'])) : '' }}</td>
            <td title="Плановое время">{{ $order['work_plan'] }}</td>
            <td title="Фактическое время">{{ $order['work_fact'] }}</td>
            <td title="Заказчик">{{ $order['customer'] }}</td>
            <td title="Заказ">{{ $order['print_title'] }}</td>
            <td title="Тираж">{{ $order['circulation'] }}</td>
            <td title="Выработка факт">{{ $order['workout_count'] }}</td>
            <td title="Выработка ОТК">{{ $order['workout_otk'] }}</td>
            <td title="Тип продукции">{{ $order['product_type'] }}</td>
            <td title="Материал1">{{ $order['mat1_id'] }}</td>
            <td title="Толщина1">{{ $order['thickness1'] }}</td>
            <td title="Материал2">{{ $order['mat2_id'] }}</td>
            <td title="Толщина2">{{ $order['thickness2'] }}</td>
            <td title="Материал3">{{ $order['mat3_id'] }}</td>
            <td title="Толщина3">{{ $order['thickness3'] }}</td>

            <td title="План материала">{{ $order['count_plan'] }}</td>
            <td title="Факт материала">{{ $order['count'] }}</td>

            <td title="Тип дна">{{ $order['bottom_type'] }}</td>
            <td title="Дно">{{ $order['bottom'] }}</td>
            <td title="Ширина">{{ $order['product_width'] }}</td>
            <td title="Длина">{{ $order['length'] }}</td>
            <td title="Карман">{{ $order['is_pocket'] ? "да" : ''}}</td>
            <td title="Ручка">{{ $order['is_handle'] ? "да" : '' }}</td>
            <td title="Кромка">{{ $order['is_edge'] ? "да" : '' }}</td>
            <td title="Перфорация">{{ $order['is_perforation'] ? "да" : ''}}</td>
            <td title="zip lock">{{ $order['is_ziplock'] ? "да" : ''}}</td>

            <td title="Отходы план">{{ $order['waste_plan'] }}</td>
            <td title="Отходы печать">{{ $order['waste_print'] }}</td>
            <td title="Отходы кромка">{{ $order['waste_edge'] }}</td>
            <td title="Отходы сварка">{{ $order['waste_welding'] }}</td>
            <td title="Отходы итог">{{ $order['waste_sum'] }}</td>

            <td title="Простой электрика">{{ $order['electro'] }}</td>
            <td title="Простой механика">{{ $order['mechanical'] }}</td>
            <td title="Скорость">{{ $order['speed'] }}</td>
            <td title="Перестройка">{{ $order['reconfig'] }}</td>
            <td title="Наладка">{{ $order['calibrating'] }}</td>
            <td title="Замена тефлона">{{ $order['change_teflon'] }}</td>
            <td title="Тех обслуживание">{{ $order['tech_service'] }}</td>
            <td title="Отсутствие людей">{{ $order['no_human'] }}</td>
            <td title="Отсутствие работы">{{ $order['no_work'] }}</td>
            <td title="Отсутствие сырья">{{ $order['no_raw'] }}</td>
            <td title="Разница тиража">{{ $order['diff_circulation'] }}</td>
            <td title="Приправка принята">{{ $order['prepare_ok'] ? "принята" : 'не принята' }}</td>
            <td title="Упаковка">{{ $order['box_size_id'] }}</td>
            <td title="Примечание">{{ $order['notes'] }}</td>
        </tr>
    @endforeach
    <tr>
        <td title="Машина"></td>
        <td title="Дата смены"></td>
        <td title="Смена"></td>
        <td title="простой всей смены"></td>
        <td title="Мастер"></td>
        <td title="Операторы"></td>
        <td title="Ученики"></td>
        <td title="Приемщик"></td>
        <td title="Номер карты">карт: {{ countUniqueTkn($data) }}</td>
        <td title="Плановое время"></td>
        <td title="Начало работ"></td>
        <td title="Окончание работ"></td>
        <td title="Фактическое время"></td>
        <td title="Заказчик"></td>
        <td title="Заказ"></td>
        <td title="Тираж"></td>
        <td title="Выработка факт">{{ summarize($data, 'workout_count') }}</td>
        <td title="Выработка ОТК">{{ summarize($data, 'workout_otk') }}</td>
        <td title="Тип продукции"></td>
        <td title="Материал1"></td>
        <td title="Толщина1"></td>
        <td title="Материал2"></td>
        <td title="Толщина2"></td>
        <td title="Материал3"></td>
        <td title="Толщина3"></td>

        <td title="План материала"></td>
        <td title="Факт материала">{{ summarize($data, 'count') }}</td>

        <td title="Тип дна"></td>
        <td title="Дно"></td>
        <td title="Ширина">{{ averaging($data, 'product_width') }}</td>
        <td title="Длина">{{ averaging($data, 'length') }}</td>
        <td title="Карман"></td>
        <td title="Ручка"></td>
        <td title="Кромка"></td>
        <td title="Перфорация"></td>
        <td title="zip lock"></td>

        <td title="Отходы план"></td>
        <td title="Отходы печать">{{ summarize($data, 'waste_print') }}</td>
        <td title="Отходы кромка">{{ summarize($data, 'waste_edge') }}</td>
        <td title="Отходы сварка">{{ summarize($data, 'waste_welding') }}</td>
        <td title="Отходы итог">{{ summarize($data, 'waste_sum') }}</td>

        <td title="Простой электрика">{{ summarize($data, 'electro') }}</td>
        <td title="Простой механика">{{ summarize($data, 'mechanical') }}</td>
        <td title="Скорость">{{ averaging($data, 'speed') }}</td>
        <td title="Перестройка">{{ summarize($data, 'reconfig') }}</td>
        <td title="Наладка">{{ summarize($data, 'calibrating') }}</td>
        <td title="Замена тефлона">{{ summarize($data, 'change_teflon') }}</td>
        <td title="Тех обслуживание">{{ summarize($data, 'tech_service') }}</td>
        <td title="Отсутствие людей">{{ summarize($data, 'no_human') }}</td>
        <td title="Отсутствие работы">{{ summarize($data, 'no_work') }}</td>
        <td title="Отсутствие сырья">{{ summarize($data, 'no_raw') }}</td>
        <td title="Разница тиража"></td>
        <td title="Приправка принята"></td>
        <td title="Упаковка"></td>
        <td title="Примечание"></td>
    </tr>
    </tbody>
</table>

</body>
</html>
