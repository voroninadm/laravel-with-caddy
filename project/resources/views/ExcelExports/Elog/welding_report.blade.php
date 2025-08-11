<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Краткий отчет сварка</title>
</head>
<body>

<p>Краткий отчет сварки c {{ $work_start }} по {{ $work_finish }}</p>
<br>
<table>
    <tr>
        <td>Количество уникальных карт</td>
        <td>{{ $data['uniqueTknCount'] }}</td>
    </tr>
    <tr>
        <th>Выработка факт</th>
        <td title="Выработка факт">{{ round($data['workout_count'], 2) }}</td>
    </tr>
    <tr>
        <th>Выработка ОТК</th>
        <td title="Выработка ОТК">{{ round($data['workout_otk'], 2) }}</td>
    </tr>
    <tr>
        <th>Факт материала</th>
        <td title="Факт материала">{{ round($data['count'], 2) }}</td>
    </tr>
    <tr>
        <th>Ширина, среднее</th>
        <td title="Ширина">{{ round($data['product_width'], 2) }}</td>
    </tr>
    <tr>
        <th>Длина, среднее</th>
        <td title="Длина">{{ round($data['length'], 2) }}</td>
    </tr>
    <tr>
        <th>Отходы печать</th>
        <td title="Отходы печать">{{ round($data['waste_print'], 2) }}</td>
    </tr>
    <tr>
        <th>Отходы кромка</th>
        <td title="Отходы кромка">{{ round($data['waste_edge'], 2) }}</td>
    </tr>
    <tr>
        <th>Отходы сварка</th>
        <td title="Отходы сварка">{{ round($data['waste_welding'], 2) }}</td>
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
        <th>Перестройка</th>
        <td title="Перестройка">{{ round($data['reconfig'], 2) }}</td>
    </tr>
    <tr>
        <th>Наладка</th>
        <td title="Наладка">{{ round($data['calibrating'], 2) }}</td>
    </tr>
    <tr>
        <th>Замена тефлона</th>
        <td title="Замена тефлона">{{ round($data['change_teflon'], 2) }}</td>
    </tr>
    <tr>
        <th>Тех обслуживание</th>
        <td title="Тех обслуживание">{{ round($data['tech_service'], 2) }}</td>
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
        <td title="Итого простой">{{ round($data['idles'], 2) }}</td>
    </tr>
</table>

</body>
</html>
