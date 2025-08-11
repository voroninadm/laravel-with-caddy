<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Краткий отчет резка</title>
</head>
<body>

<p>Краткий отчет резки c {{ $work_start }} по {{ $work_finish }}</p>
<br>
<table>
    <tr>
        <td>Количество уникальных карт</td>
        <td>{{ $data['uniqueTknCount'] }}</td>
    </tr>
    <tr>
        <th>Выработка кг</th>
        <td title="Выработка кг">{{ round($data['workout_mass'], 2) }}</td>
    </tr>
    <tr>
        <th>Выработка м.пог</th>
        <td title="Выработка м.пог">{{ round($data['workout_length'], 2) }}</td>
    </tr>
    <tr>
        <th>Выработка м<sup>2</sup></th>
        <td title="Выработка м2">{{ round($data['workout_m2'], 2) }}</td>
    </tr>
    <tr>
        <th>Выработка ОТК</th>
        <td title="Выработка ОТК">{{ round($data['otk_mass'], 2) }}</td>
    </tr>
    <tr>
        <th>Выработка м.сырьевые</th>
        <td title="Выработка сырьевые метры">{{ round($data['raw_meters'], 2) }}</td>
    </tr>
    <tr>
        <th>Отходы печать</th>
        <td title="Отходы печать">{{ round($data['waste_print'], 2) }}</td>
    </tr>
    <tr>
        <th>Отходы ламинация</th>
        <td title="Отходы ламинация">{{ round($data['waste_lam'], 2) }}</td>
    </tr>
    <tr>
        <th>Отходы кромка</th>
        <td title="Отходы кромка">{{ round($data['waste_edge'], 2) }}</td>
    </tr>
    <tr>
        <th>Отходы итог</th>
        <td title="Отходы итог">{{ round($data['waste_sum'], 2) }}</td>
    </tr>
    <tr>
        <th>Простой электрика</th>
        <td title="Простой электрика">{{ round($data['electro'], 2) }}</td>
    </tr>
    <tr>
        <th>Простой механика</th>
        <td title="Простой механика">{{ round($data['mechanical'], 2) }}</td>
    </tr>
    <tr>
        <th>Скорость, среднее</th>
        <td title="Скорость">{{ round($data['speed'], 2) }}</td>
    </tr>
    <tr>
        <th>Техническое обслуживание</th>
        <td title="Тех обслуживание">{{ round($data['tech_service'], 2) }}</td>
    </tr>
    <tr>
        <th>Замена ножей/штанги</th>
        <td title="Приправка ножей/штанги">{{ round($data['knifes_barbell'], 2) }}</td>
    </tr>
    <tr>
        <th>Перенастройка</th>
        <td title="Перестройка">{{ round($data['reconfig'], 2) }}</td>
    </tr>
    <tr>
        <th>Отсутствие людей</th>
        <td title="Отсутствие людей">{{ round($data['no_human'], 2) }}</td>
    </tr>
    <tr>
        <th>Отсутствие работы</th>
        <td title="Отсутствие работы">{{ round($data['no_work'], 2) }}</td>
    </tr>
    <tr>
        <th>Отсутствие сырья</th>
        <td title="Отсутствие сырья">{{ round($data['no_raw'], 2) }}</td>
    </tr>
    <tr>
        <th>Итого простой</th>
        <td title="Итого простой">{!! round($data['idles'], 2) !!}</td>
    </tr>
</table>

</body>
</html>
