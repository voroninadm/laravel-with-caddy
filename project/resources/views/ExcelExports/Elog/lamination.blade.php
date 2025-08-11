<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчет ламинация</title>
</head>
<body>

<p>Отчет ламинации c {{ $work_start }} по {{ $work_finish }}</p>
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
        <th>Ученик</th>
        <th>Подсобный рабочий</th>
        <th>Номер карты</th>
        <th>Плановое время</th>
        <th>Начало работ</th>
        <th>Окончание работ</th>
        <th>Фактическое время</th>
        <th>Время выпуска</th>
        <th>Заказчик</th>
        <th>Заказ</th>
        <th>Тираж</th>
        <th>Материал-1</th>
        <th>План материала-1</th>
        <th>Факт материала-1</th>
        <th>Ширина-1</th>
        <th>Толщина-1</th>
        <th>Остаток первички</th>
        <th>Материал-2</th>
        <th>План материала-2</th>
        <th>Факт материала-2</th>
        <th>Ширина-2</th>
        <th>Толщина-2</th>
        <th>Остаток вторички</th>
        <th>Покрытие</th>
        <th>Факт покрытия</th>
        <th>Ширина покрытия</th>
        <th>Толщина покрытия</th>
        <th>Выработка кг</th>
        <th>Выработка м.пог</th>
        <th>Выработка м<sup>2</sup></th>
        <th>Выработка ОТК</th>
        <th>Разница тиража</th>
        <th>Отходы план</th>
        <th>Отходы печать</th>
        <th>Отходы ламинация</th>
        <th>Отходы итог</th>
        <th>Приправка</th>
        <th>Техчистка</th>
        <th>Замена клея</th>
        <th>Простой электрика</th>
        <th>Простой механика</th>
        <th>Техническое обслуживание</th>
        <th>Отсутствие людей</th>
        <th>Отсутствие работы</th>
        <th>Отсутствие сырья</th>

        <th>Краткосрочный простой</th>
        <th>Простой без причины</th>
        <th>Общий простой</th>

        <th>Приправка принята</th>
        <th>Примечание</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $order)
        <tr>
            <td title="Машина">{{ $order['machine_id'] }}</td>
            <td title="Дата смены">{{ date('d.m.Y', strtotime($order['work_date'])) }}</td>
            <td title="Смена">{{ $order['work_shift'] }}</td>
            <td title="Простой всей смены">{{ $order['is_idle'] ? 'да' : '' }}</td>
            <td title="Мастер">{{ $order['master_id'] }}</td>
            <td title="Операторы">{{ implode(" ", array_filter([$order['operator1_id'], $order['operator2_id'], $order['operator3_id']])) }}</td>
            <td title="Ученик">{{ $order['student_id'] }}</td>
            <td title="подсобный рабочий">{{ $order['helper_id'] }}</td>
            <td title="Номер карты">{{ $order['tkn'] }}</td>
            <td title="Плановое время">{{ $order['work_plan'] }}</td>
            <td title="Начало работ">{{ $order['work_start'] ? date('d.m.Y h:m', strtotime($order['work_start'])) : '' }}</td>
            <td title="Окончание работ">{{ $order['work_finish'] ? date('d.m.Y h:m', strtotime($order['work_finish'])) : '' }}</td>
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
            <td title="Остаток первички">{{ $order['remain_perv'] }}</td>

            <td title="Материал-2">{{ $order['mat2_id'] }}</td>
            <td title="План материала-2">{{ $order['mat2count_plan'] }}</td>
            <td title="Факт материала-2">{{ $order['mat2count'] }}</td>
            <td title="Ширина-2">{{ $order['width2'] }}</td>
            <td title="Толщина-2">{{ $order['thickness2'] }}</td>
            <td title="Остаток вторички">{{ $order['remain_sec'] }}</td>
            <td title="Покрытие">{{ $order['covering_id'] }}</td>
            <td title="Факт покрытия">{{ $order['covering_count'] }}</td>
            <td title="Ширина покрытия">{{ $order['covering_width'] }}</td>
            <td title="Толщина покрытия">{{ $order['covering_thickness'] }}</td>

            <td title="Выработка кг">{{ $order['workout_mass'] }}</td>
            <td title="Выработка м.пог">{{ $order['workout_length'] }}</td>
            <td title="Выработка м2">{{ $order['workout_m2'] }}</td>
            <td title="Выработка ОТК">{{ $order['otk_mass'] }}</td>
            <td title="Разница тиража">{{ $order['diff_circulation'] }}</td>
            <td title="Отходы план">{{ $order['waste_plan'] }}</td>
            <td title="Отходы печать">{{ $order['waste_print'] }}</td>
            <td title="Отходы сырье">{{ $order['waste_lam'] }}</td>
            <td title="Отходы итог">{{ $order['waste_sum'] }}</td>

            <td title="Приправка">{{ $order['prepare_hours'] }}</td>
            <td title="Техчистка">{{ $order['tech_clean'] }}</td>
            <td title="Замена клея">{{ $order['change_glue'] }}</td>
            <td title="Простой электрика">{{ $order['electro'] }}</td>
            <td title="Простой механика">{{ $order['mechanical'] }}</td>
            <td title="Тех обслуживание">{{ $order['tech_service'] }}</td>
            <td title="Отсутствие людей">{{ $order['no_human'] }}</td>
            <td title="Отсутствие работы">{{ $order['no_work'] }}</td>
            <td title="Отсутствие сырья">{{ $order['no_raw'] }}</td>

            <td title="Краткосрочный простой">{{ $order['short_downtime'] }}</td>
            <td title="Простой без причины">{{ $order['no_reason_downtime'] }}</td>
            <td title="Общий простой">{{ $order['total_downtime'] }}</td>

            <td title="Приправка принята">{{ $order['prepare_ok'] ? "принята": 'не принята'}}</td>
            <td title="Примечание">{{ $order['notes'] }}</td>
        </tr>
    @endforeach
    <tr>
        <td title="Машина"></td>
        <td title="Дата смены"></td>
        <td title="Смена"></td>
        <td title="Простой всей смены"></td>
        <td title="Мастер"></td>
        <td title="Операторы"></td>
        <td title="Ученик"></td>
        <td title="подсобный рабочий"></td>
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
        <td title="Остаток первички"></td>
        <td title="Материал-2"></td>
        <td title="План материала-2"></td>
        <td title="Факт материала-2">{{ summarize($data, 'mat2count') }}</td>
        <td title="Ширина-2">{{ averaging($data, 'width2') }}</td>
        <td title="Толщина-2">{{ averaging($data, 'thickness2') }}</td>
        <td title="Остаток вторички"></td>
        <td title="Покрытие"></td>
        <td title="Факт покрытия">{{ summarize($data, 'covering_count') }}</td>
        <td title="Ширина покрытия">{{ averaging($data, 'covering_width') }}</td>
        <td title="Толщина покрытия">{{ averaging($data, 'covering_thickness') }}</td>

        <td title="Выработка кг">{{ summarize($data, 'workout_mass') }}</td>
        <td title="Выработка м.пог">{{ summarize($data, 'workout_length') }}</td>
        <td title="Выработка м2">{{ summarize($data, 'workout_m2') }}</td>
        <td title="Выработка ОТК">{{ summarize($data, 'otk_mass') }}</td>
        <td title="Разница тиража"></td>
        <td title="Отходы план"></td>
        <td title="Отходы печать">{{ summarize($data, 'waste_print') }}</td>
        <td title="Отходы ламинация">{{ summarize($data, 'waste_lam') }}</td>
        <td title="Отходы итог">{{ summarize($data, 'waste_sum') }}</td>

        <td title="Приправка">{{ summarize($data, 'prepare_hours') }}</td>
        <td title="Техчистка">{{ summarize($data, 'tech_clean') }}</td>
        <td title="Замена клея">{{ summarize($data, 'change_glue') }}</td>
        <td title="Простой электрика">{{ summarize($data, 'electro') }}</td>
        <td title="Простой механика">{{ summarize($data, 'mechanical') }}</td>
        <td title="Тех обслуживание">{{ summarize($data, 'tech_service') }}</td>
        <td title="Отсутствие людей">{{ summarize($data, 'no_human') }}</td>
        <td title="Отсутствие работы">{{ summarize($data, 'no_work') }}</td>
        <td title="Отсутствие сырья">{{ summarize($data, 'no_raw') }}</td>

        <td title="Краткосрочный простой">{{ summarize($data, 'short_downtime') }}</td>
        <td title="Простой без причины">{{ summarize($data, 'no_reason_downtime') }}</td>
        <td title="Общий простой">{{ summarize($data, 'total_downtime') }}</td>

        <td title="Приправка принята"></td>
        <td title="Примечание"></td>
    </tr>
    </tbody>
</table>

</body>
</html>
