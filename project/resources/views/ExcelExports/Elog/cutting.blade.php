<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчет резка</title>
</head>
<body>

<p>Отчет резки c {{ $work_start }} по {{ $work_finish }}</p>
<br>
<table>
    <thead>
    <tr>
        <th>Машина</th>
        <th>Дата смены</th>
        <th>Cмена</th>
        <th>Простой всей смены</th>
        <th>Мастер</th>
        <th>Оператор</th>
        <th>Упаковщик</th>
        <th>Ученик</th>
        <th>Номер карты</th>
        <th>Плановое время</th>
        <th>Начало работ</th>
        <th>Окончание работ</th>
        <th>Фактическое время</th>
        <th>Заказчик</th>
        <th>Заказ</th>
        <th>Тираж</th>

        <th>Тип продукта</th>
        <th>Материал-1</th>
        <th>Толщина-1</th>
        <th>Материал-2</th>
        <th>Толщина-2</th>
        <th>Материал-3</th>
        <th>Толщина-3</th>
        <th>Ширина полотна</th>
        <th>План материала</th>
        <th>Факт материала</th>
        <th>Количествово ручьев</th>
        <th>Ширина ручья</th>

        <th>Выработка кг</th>
        <th>Выработка м.пог</th>
        <th>Выработка м<sup>2</sup></th>
        <th>Выработка ОТК</th>
        <th>Выработка м.сырьевые</th>
        <th>Отходы план</th>
        <th>Отходы печать</th>
        <th>Отходы ламинация</th>
        <th>Отходы кромка</th>
        <th>Отходы итог</th>

        <th>Простой электрика</th>
        <th>Простой механика</th>
        <th>Скорость</th>
        <th>Техническое обслуживание</th>
        <th>Замена ножей/штанги</th>
        <th>Перенастройка</th>
        <th>Отсутствие людей</th>
        <th>Отсутствие работы</th>
        <th>Отсутствие сырья</th>
        <th>Разница тиража</th>
        <th>Приправка</th>
        <th>Примечание</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $order)
        <tr>
            <td title="Машина">{{ $order['machine_id'] }}</td>
            <td title="Дата смены">{{ date('d.m.Y', strtotime($order['work_date'])) }}</td>
            <td title="Смена">{{ $order['work_shift'] }}</td>
            <td title="Смена простоя">{{ $order['is_idle'] ? 'да' : '' }}</td>
            <td title="Мастер">{{ $order['master_id'] }}</td>
            <td title="Оператор">{{ $order['operator_id'] }}</td>
            <td title="Упаковщик">{{ $order['packer_id'] }}</td>
            <td title="Ученик">{{ $order['student_id'] }}</td>
            <td title="Номер карты">{{ $order['tkn'] }}</td>
            <td title="Плановое время">{{ $order['work_plan'] }}</td>
            <td title="Начало работ">{{ $order['work_start'] ? date('d.m.Y h:m', strtotime($order['work_start'])) : '' }}</td>
            <td title="Окончание работ">{{ $order['work_start'] ? date('d.m.Y h:m', strtotime($order['work_finish'])) : '' }}</td>
            <td title="Фактическое время">{{ $order['work_fact'] }}</td>
            <td title="Заказчик">{{ $order['customer'] }}</td>
            <td title="Заказ">{{ $order['print_title'] }}</td>
            <td title="Тираж">{{ $order['circulation'] }}</td>

            <td title="Тип продукта">{{ $order['product_type'] }}</td>
            <td title="Материал">{{ $order['mat1_id'] }}</td>
            <td title="Толщина">{{ $order['thickness1'] }}</td>
            <td title="Материал-2">{{ $order['mat2_id'] }}</td>
            <td title="Толщина-2">{{ $order['thickness2'] }}</td>
            <td title="Материал-3">{{ $order['mat3_id'] }}</td>
            <td title="Толщина-3">{{ $order['thickness3'] }}</td>

            <td title="Ширина полотна">{{ $order['canvas_width'] }}</td>
            <td title="План">{{ $order['count_plan'] }}</td>
            <td title="Факт">{{ $order['count'] }}</td>
            <td title="Количество ручьев">{{ $order['streams'] }}</td>
            <td title="Ширина ручья">{{ $order['stream_width'] }}</td>

            <td title="Выработка кг">{{ $order['workout_mass'] }}</td>
            <td title="Выработка м.пог">{{ $order['workout_length'] }}</td>
            <td title="Выработка м2">{{ $order['workout_m2'] }}</td>
            <td title="Выработка ОТК">{{ $order['otk_mass'] }}</td>
            <td title="Сырьевые метры">{{ $order['raw_meters'] }}</td>

            <td title="Отходы план">{{ $order['waste_plan'] }}</td>
            <td title="Отходы печать">{{ $order['waste_print'] }}</td>
            <td title="Отходы ламинация">{{ $order['waste_lam'] }}</td>
            <td title="Отходы кромка">{{ $order['waste_edge'] }}</td>
            <td title="Отходы итог">{{ $order['waste_sum'] }}</td>

            <td title="Простой электрика">{{ $order['electro'] }}</td>
            <td title="Простой механика">{{ $order['mechanical'] }}</td>
            <td title="скорость">{{ $order['speed'] }}</td>
            <td title="Тех обслуживание">{{ $order['tech_service'] }}</td>
            <td title="Замена ножей/штанги">{{ $order['knifes_barbell'] }}</td>

            <td title="Перестройка">{{ $order['reconfig'] }}</td>
            <td title="Отсутствие людей">{{ $order['no_human'] }}</td>
            <td title="Отсутствие работы">{{ $order['no_work'] }}</td>
            <td title="Отсутствие сырья">{{ $order['no_raw'] }}</td>
            <td title="Разница тиража">{{ $order['diff_circulation'] }}</td>
            <td title="Приправка">{{ $order['prepare_ok'] ? "принята" : 'не принята' }}</td>
            <td title="Примечание">{{ $order['notes'] }}</td>
        </tr>
    @endforeach
    <tr>
        <td title="Машина"></td>
        <td title="Дата смены"></td>
        <td title="Смена"></td>
        <td title="Смена простоя"></td>
        <td title="Мастер"></td>
        <td title="Оператор"></td>
        <td title="Упаковщик"></td>
        <td title="Ученик"></td>
        <td title="Номер карты">карт: {{ countUniqueTkn($data) }}</td>
        <td title="Плановое время"></td>
        <td title="Начало работ"></td>
        <td title="Окончание работ"></td>
        <td title="Фактическое время"></td>
        <td title="Заказчик"></td>
        <td title="Заказ"></td>
        <td title="Тираж"></td>

        <td title="Тип продукта"></td>
        <td title="Материал"></td>
        <td title="Толщина">{{ averaging($data, 'thickness1') }}</td>
        <td title="Материал-2"></td>
        <td title="Толщина-2">{{ averaging($data, 'thickness2') }}</td>
        <td title="Материал-3"></td>
        <td title="Толщина-3">{{ averaging($data, 'thickness3') }}</td>

        <td title="Ширина полотна"></td>
        <td title="План"></td>
        <td title="Факт">{{ summarize($data, 'count') }}</td>
        <td title="Количество ручьев"></td>
        <td title="ширина ручья"></td>

        <td title="Выработка кг">{{ summarize($data, 'workout_mass') }}</td>
        <td title="Выработка м.пог">{{ summarize($data, 'workout_length') }}</td>
        <td title="Выработка м2">{{ summarize($data, 'workout_m2') }}</td>
        <td title="Выработка ОТК">{{ summarize($data, 'otk_mass') }}</td>
        <td title="Выработка сырьевые метры">{{ summarize($data, 'raw_meters') }}</td>
        <td title="Отходы план"></td>
        <td title="Отходы печать">{{ summarize($data, 'waste_print') }}</td>
        <td title="Отходы ламинация">{{ summarize($data, 'waste_lam') }}</td>
        <td title="Отходы кромка">{{ summarize($data, 'waste_edge') }}</td>
        <td title="Отходы итог">{{ summarize($data, 'waste_sum') }}</td>

        <td title="Простой электрика">{{ summarize($data, 'electro') }}</td>
        <td title="Простой механика">{{ summarize($data, 'mechanical') }}</td>
        <td title="Скорость">{{ averaging($data, 'speed') }}</td>
        <td title="Тех обслуживание">{{ summarize($data, 'tech_service') }}</td>

        <td title="Приправка ножей/штанги">{{ summarize($data, 'knifes_barbell') }}</td>
        <td title="Перестройка">{{ summarize($data, 'reconfig') }}</td>

        <td title="Отсутствие людей">{{ summarize($data, 'no_human') }}</td>
        <td title="Отсутствие работы">{{ summarize($data, 'no_work') }}</td>
        <td title="Отсутствие сырья">{{ summarize($data, 'no_raw') }}</td>
        <td title="Разница тиража"></td>
        <td title="Приправка"></td>
        <td title="Примечание"></td>
    </tr>
    </tbody>
</table>

</body>
</html>
