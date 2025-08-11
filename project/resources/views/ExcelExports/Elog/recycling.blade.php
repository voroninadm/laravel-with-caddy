<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчет переработка</title>
</head>
<body>

<p>Отчет переработки c {{ $work_start }} по {{ $work_finish }}</p>
<br>
<table>
    <thead>
    <tr>
        <th>Машина</th>
        <th>Дата смены</th>
        <th>Cмена</th>
        <th>Смена простоя</th>
        <th>Мастер</th>
        <th>Машинист</th>
        <th>Подсобный рабочий</th>
        <th>Начало работ</th>
        <th>Окончание работ</th>
        <th>Фактическое время</th>

        <th>Номенклатура гранулята</th>
        <th>Выработка кг</th>

        <th>Номенклатура литника</th>
        <th>Масса литников</th>

        <th>Простой электрика</th>
        <th>Простой механика</th>
        <th>Простой электроника</th>
        <th>Техническое облуживание</th>

        <th>Замена сеток</th>
        <th>Замена ножей</th>
        <th>Замена резцов</th>
        <th>Чистка машины</th>
        <th>Мойка машины</th>
        <th>Приправка</th>
        <th>Отсутствие людей</th>
        <th>Отсутствие работы</th>
        <th>Отсутствие сырья</th>
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
            <td title="Машинист">{{ $order['machinist_id'] }}</td>
            <td title="Подсобный рабочий">{{ $order['helper_id'] }}</td>
            <td title="Начало работ">{{ $order['work_start'] ? date('d.m.Y h:m', strtotime($order['work_start'])) : '' }}</td>
            <td title="Окончание работ">{{ $order['work_start'] ? date('d.m.Y h:m', strtotime($order['work_finish'])) : '' }}</td>
            <td title="Фактическое время">{{ $order['work_fact'] }}</td>

            <td title="Тип гранулята">{{ $order['nomenclature'] }}</td>
            <td title="Выработка кг">{{ $order['workout_mass'] }}</td>

            <td title="Тип литника">{{ $order['ingots_type'] }}</td>
            <td title="Масса литников">{{ $order['waste_mass'] }}</td>

            <td title="Простой электрика">{{ $order['electro'] }}</td>
            <td title="Простой механика">{{ $order['mechanical'] }}</td>
            <td title="Простой электроника">{{ $order['electronics'] }}</td>
            <td title="Тех.обслуживание">{{ $order['tech_service'] }}</td>
            <td title="Замена сеток">{{ $order['change_net'] }}</td>
            <td title="Замена ножей">{{ $order['change_knifes'] }}</td>
            <td title="Замена резцов">{{ $order['change_cutter'] }}</td>
            <td title="Чистка машины">{{ $order['tech_clean'] }}</td>
            <td title="Мойка машины">{{ $order['clean_machine'] }}</td>
            <td title="Приправка">{{ $order['prepare_hours'] }}</td>
            <td title="Отсутствие людей">{{ $order['no_human'] }}</td>
            <td title="Отсутствие работы">{{ $order['no_work'] }}</td>
            <td title="Отсутствие сырья">{{ $order['no_raw'] }}</td>
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
        <td title="Машинист"></td>
        <td title="Подсобный рабочий"></td>
        <td title="Начало работ"></td>
        <td title="Окончание работ"></td>
        <td title="Фактическое время"></td>

        <td title="Тип гранулята"></td>
        <td title="Выработка кг">{{ summarize($data, 'workout_mass') }}</td>

        <td title="Тип литника"></td>
        <td title="Масса литников">{{ summarize($data, 'waste_mass') }}</td>

        <td title="Простой электрика">{{ summarize($data, 'electro') }}</td>
        <td title="Простой механика">{{ summarize($data, 'mechanical') }}</td>
        <td title="Простой электроника">{{ summarize($data, 'electronics') }}</td>
        <td title="Тех обслуживание">{{ summarize($data, 'tech_service') }}</td>
        <td title="Замена сеток">{{ summarize($data, 'change_net') }}</td>
        <td title="Замена ножей">{{ summarize($data, 'change_knifes') }}</td>
        <td title="Замена резцов">{{ summarize($data, 'change_cutter') }}</td>
        <td title="Чистка машины">{{ summarize($data, 'tech_clean') }}</td>
        <td title="Мойка машины">{{ summarize($data, 'clean_machine') }}</td>
        <td title="Приправка">{{ summarize($data, 'prepare_hours') }}</td>

        <td title="Отсутствие людей">{{ summarize($data, 'no_human') }}</td>
        <td title="Отсутствие работы">{{ summarize($data, 'no_work') }}</td>
        <td title="Отсутствие сырья">{{ summarize($data, 'no_raw') }}</td>
        <td title="Приправка принята"></td>
        <td title="Примечание"></td>
    </tr>
    </tbody>
</table>

</body>
</html>
