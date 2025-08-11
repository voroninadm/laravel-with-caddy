<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчет экструзия</title>
</head>
<body>

<p>Отчет экструзии c {{ $work_start }} по {{ $work_finish }}</p>
<br>
<table>
    <thead>
    <tr>
        <th>Машина</th>
        <th>Дата смены</th>
        <th>Cмена</th>
        <th>Смена простоя</th>
        <th>Мастер</th>
        <th>Машинист-1</th>
        <th>Машинист-2</th>
        <th>Машинист-3</th>
        <th>Ученик</th>
        <th>Номер карты</th>
        <th>Номер рецептуры</th>
        <th>Номер партии</th>
        <th>Плановое время</th>
        <th>Начало работ</th>
        <th>Окончание работ</th>
        <th>Фактическое время</th>
        <th>Заказчик</th>
        <th>Заказ</th>
        <th>Тираж кг</th>
        <th>Тираж пог.м.</th>

        <th>Тип продукта</th>
        <th>Ширина</th>
        <th>Толщина</th>
        <th>Поле активации</th>
        <th>Кромка</th>
        <th>Количество потоков</th>

        <th>Масса рулона</th>
        <th>Длина рулона</th>
        <th>Диаметр рулона</th>

        <th>Выработка кг</th>
        <th>Выработка м.пог</th>
        <th>Выработка м<sup>2</sup></th>
        <th>Выработка ОТК</th>

        <th>Отходы план рулонные</th>
        <th>Отходы план кромки</th>
        <th>Отходы план слитки</th>
        <th>Отходы рулонные</th>
        <th>Отходы переходные</th>
        <th>Отходы кромки</th>
        <th>Отходы слитки</th>
        <th>Отходы срезы</th>
        <th>Отходы итог</th>

        <th>Простой электрика</th>
        <th>Простой механика</th>
        <th>Простой электроника</th>
        <th>Техническое облуживание</th>
        <th>Замена сеток</th>
        <th>Замена сырья</th>
        <th>Замена заказа</th>
        <th>Мойка машины</th>
        <th>Приправка</th>
        <th>Отсутствие людей</th>
        <th>Отсутствие работы</th>
        <th>Отсутствие сырья</th>
        <th>Разница тиража</th>
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
            <td title="Смена простоя">{{ $order['is_idle'] ? 'да' : '' }}</td>
            <td title="Мастер">{{ $order['master_id'] }}</td>
            <td title="Машинист-1">{{ $order['machinist1_id'] }}</td>
            <td title="Машинист-2">{{ $order['machinist2_id'] }}</td>
            <td title="Машинист-3">{{ $order['machinist3_id'] }}</td>
            <td title="Ученик">{{ $order['student_id'] }}</td>
            <td title="Номер карты">{{ $order['tkn'] }}</td>
            <td title="Номер рецептуры">{{ $order['recipe_number'] }}</td>
            <td title="Номер партии">{{ $order['part_number'] }}</td>
            <td title="Плановое время">{{ $order['work_plan'] }}</td>
            <td title="Начало работ">{{ $order['work_start'] ? date('d.m.Y h:m', strtotime($order['work_start'])) : '' }}</td>
            <td title="Окончание работ">{{ $order['work_start'] ? date('d.m.Y h:m', strtotime($order['work_finish'])) : '' }}</td>
            <td title="Фактическое время">{{ $order['work_fact'] }}</td>
            <td title="Заказчик">{{ $order['customer'] }}</td>
            <td title="Заказ">{{ $order['print_title'] }}</td>
            <td title="Тираж кг">{{ $order['circulation_kg'] }}</td>
            <td title="Тираж пог м">{{ $order['circulation_length'] }}</td>

            <td title="Тип продукта">{{ $order['product_type'] }}</td>
            <td title="Ширина">{{ $order['width'] }}</td>
            <td title="Толщина">{{ $order['thickness'] }}</td>
            <td title="Поле активации">{{ $order['activation_pole'] }}</td>
            <td title="Кромка">{{ $order['edge'] }}</td>
            <td title="Кол-во потоков">{{ $order['streams'] }}</td>

            <td title="Масса рулона">{{ $order['roll_mass'] }}</td>
            <td title="Длина рулона">{{ $order['roll_length'] }}</td>
            <td title="Диаметр рулона">{{ $order['roll_diameter'] }}</td>

            <td title="Выработка кг">{{ $order['workout_mass'] }}</td>
            <td title="Выработка м.пог">{{ $order['workout_length'] }}</td>
            <td title="Выработка м2">{{ $order['workout_m2'] }}</td>
            <td title="Выработка ОТК">{{ $order['workout_otk'] }}</td>

            <td title="Отходы план рулон">{{ $order['waste_plan_roll'] }}</td>
            <td title="Отходы план кромка">{{ $order['waste_plan_edge'] }}</td>
            <td title="Отходы план слитки">{{ $order['waste_plan_ingets'] }}</td>
            <td title="Отходы рулон">{{ $order['waste_roll'] }}</td>
            <td title="Отходы переходные">{{ $order['waste_trans'] }}</td>
            <td title="Отходы кромка">{{ $order['waste_edge'] }}</td>
            <td title="Отходы слитки">{{ $order['waste_ingets'] }}</td>
            <td title="Отходы срезы">{{ $order['waste_slice'] }}</td>
            <td title="Отходы итог">{{ $order['waste_sum'] }}</td>

            <td title="Простой электрика">{{ $order['electro'] }}</td>
            <td title="Простой механика">{{ $order['mechanical'] }}</td>
            <td title="Простой электроника">{{ $order['electronics'] }}</td>
            <td title="Тех.обслуживание">{{ $order['tech_service'] }}</td>
            <td title="Замена сеток">{{ $order['change_net'] }}</td>
            <td title="Замена сырья">{{ $order['change_raw'] }}</td>
            <td title="Замена заказа">{{ $order['change_order'] }}</td>
            <td title="Мойка машины">{{ $order['clean_machine'] }}</td>
            <td title="Приправка">{{ $order['prepare_hours'] }}</td>
            <td title="Отсутствие людей">{{ $order['no_human'] }}</td>
            <td title="Отсутствие работы">{{ $order['no_work'] }}</td>
            <td title="Отсутствие сырья">{{ $order['no_raw'] }}</td>
            <td title="Разница тиража">{{ $order['diff_circulation'] }}</td>
            <td title="Приправка принята">{{ $order['prepare_ok'] ? "принята" : 'не принята' }}</td>
            <td title="Примечание">{{ $order['notes'] }}</td>
        </tr>
    @endforeach
    <tr>
        <td title="Машина"></td>
        <td title="Дата смены"></td>
        <td title="Смена"></td>
        <td title="Смена простоя"></td>
        <td title="Мастер"></td>
        <td title="Машинист-1"></td>
        <td title="Машинист-2"></td>
        <td title="Машинист-3"></td>
        <td title="Ученик"></td>
        <td title="Номер карты">карт: {{ countUniqueTkn($data) }}</td>
        <td title="Номер рецептуры"></td>
        <td title="Номер партии"></td>
        <td title="Плановое время"></td>
        <td title="Начало работ"></td>
        <td title="Окончание работ"></td>
        <td title="Фактическое время"></td>
        <td title="Заказчик"></td>
        <td title="Заказ"></td>
        <td  title="Тираж кг"></td>
        <td  title="Тираж м.пог"></td>

        <td title="Тип продукта"></td>
        <td title="Ширина">{{ averaging($data, 'width') }}</td>
        <td title="Толщина">{{ averaging($data, 'thickness') }}</td>
        <td title="Поле активации"></td>
        <td title="Кромка"></td>
        <td title="Кол-во потоков"></td>

        <td title="Масса рулона"></td>
        <td title="Длина рулона"></td>
        <td title="Диаметр рулона"></td>

        <td title="Выработка кг">{{ summarize($data, 'workout_mass') }}</td>
        <td title="Выработка пог.м.">{{ summarize($data, 'workout_length') }}</td>
        <td title="Выработка м2">{{ summarize($data, 'workout_m2') }}</td>
        <td title="Выработка ОТК">{{ summarize($data, 'workout_otk') }}</td>

        <td title="Отходы план рулон"></td>
        <td title="Отходы план кромка"></td>
        <td title="Отходы план слитки"></td>
        <td title="Отходы рулон">{{ summarize($data, 'waste_roll') }}</td>
        <td title="Отходы переходные">{{ summarize($data, 'waste_trans') }}</td>
        <td title="Отходы кромка">{{ summarize($data, 'waste_edge') }}</td>
        <td title="Отходы слитки">{{ summarize($data, 'waste_ingets') }}</td>
        <td title="Отходы срезы">{{ summarize($data, 'waste_slice') }}</td>
        <td title="Отходы итог">{{ summarize($data, 'waste_sum') }}</td>

        <td title="Простой электрика">{{ summarize($data, 'electro') }}</td>
        <td title="Простой механика">{{ summarize($data, 'mechanical') }}</td>
        <td title="Простой электроника">{{ summarize($data, 'electronics') }}</td>
        <td title="Тех обслуживание">{{ summarize($data, 'tech_service') }}</td>
        <td title="Замена сеток">{{ summarize($data, 'change_net') }}</td>
        <td title="Замена сырья">{{ summarize($data, 'change_raw') }}</td>
        <td title="Замена заказа">{{ summarize($data, 'change_order') }}</td>
        <td title="Мойка машины">{{ summarize($data, 'clean_machine') }}</td>
        <td title="Приправка">{{ summarize($data, 'prepare_hours') }}</td>

        <td title="Отсутствие людей">{{ summarize($data, 'no_human') }}</td>
        <td title="Отсутствие работы">{{ summarize($data, 'no_work') }}</td>
        <td title="Отсутствие сырья">{{ summarize($data, 'no_raw') }}</td>
        <td title="Разница тиража"></td>
        <td title="Приправка принята"></td>
        <td title="Примечание"></td>
    </tr>
    </tbody>
</table>

</body>
</html>
