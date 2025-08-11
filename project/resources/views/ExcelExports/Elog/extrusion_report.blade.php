<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Краткий отчет экструзия</title>
</head>
<body>

<p>Краткий отчет экструзии c {{ $work_start }} по {{ $work_finish }}</p>
<br>
<table>
    <tr>
        <td>Количество уникальных карт</td>
        <td>{{ $data['uniqueTknCount'] }}</td>
    </tr>
    <tr>
        <td>Ширина, среднее</td>
        <td>{{ round($data['width'], 2) }}</td>
    </tr>
    <tr>
        <td>Толщина, среднее</td>
        <td>{{ round($data['thickness'], 2) }}</td>
    </tr>
    <tr>
        <td>Выработка, кг</td>
        <td>{{ round($data['workout_mass'], 2) }}</td>
    </tr>
    <tr>
        <td>Выработка, погонные метры</td>
        <td>{{ round($data['workout_length'], 2) }}</td>
    </tr>
    <tr>
        <td>Выработка, м<sup>2</sup></td>
        <td>{{ round($data['workout_m2'], 2) }}</td>
    </tr>
    <tr>
        <td>Выработка на ОТК</td>
        <td>{{ round($data['workout_otk'], 2) }}</td>
    </tr>
    <tr>
        <td>Отходы рулонные</td>
        <td>{{ round($data['waste_roll'], 2) }}</td>
    </tr>
    <tr>
        <td>Отходы переходные</td>
        <td>{{ round($data['waste_trans'], 2) }}</td>
    </tr>
    <tr>
        <td>Отходы кромка</td>
        <td>{{ round($data['waste_edge'], 2) }}</td>
    </tr>
    <tr>
        <td>Отходы слитки</td>
        <td>{{ round($data['waste_ingets'], 2) }}</td>
    </tr>
    <tr>
        <td>Отходы срезы</td>
        <td>{{ round($data['waste_slice'], 2) }}</td>
    </tr>
    <tr>
        <td>Отходы итоговые</td>
        <td>{{ round($data['waste_sum'], 2) }}</td>
    </tr>
    <tr>
        <td>Простой электрика</td>
        <td>{{ round($data['electro'], 2) }}</td>
    </tr>
    <tr>
        <td>Простой механика</td>
        <td>{{ round($data['mechanical'], 2) }}</td>
    </tr>
    <tr>
        <td>Простой электроника</td>
        <td>{{ round($data['electronics'], 2) }}</td>
    </tr>
    <tr>
        <td>Техническое обслуживание</td>
        <td>{{ round($data['tech_service'], 2) }}</td>
    </tr>
    <tr>
        <td>Замена сеток</td>
        <td>{{ round($data['change_net'], 2) }}</td>
    </tr>
    <tr>
        <td>Замена сырья</td>
        <td>{{ round($data['change_raw'], 2) }}</td>
    </tr>
    <tr>
        <td>Замена заказа</td>
        <td>{{ round($data['change_order'], 2) }}</td>
    </tr>
    <tr>
        <td>Мойка машины</td>
        <td>{{ round($data['clean_machine'], 2) }}</td>
    </tr>
    <tr>
        <td>Приправка, часы</td>
        <td>{{ round($data['prepare_hours'], 2) }}</td>
    </tr>
    <tr>
        <td>Отсутствие людей</td>
        <td>{{ round($data['no_human'], 2) }}</td>
    </tr>
    <tr>
        <td>Отсутствие работы</td>
        <td>{{ round($data['no_work'], 2) }}</td>
    </tr>
    <tr>
        <td>Отсутствие сырья</td>
        <td>{{ round($data['no_raw'], 2) }}</td>
    </tr>
</table>

</body>
</html>
