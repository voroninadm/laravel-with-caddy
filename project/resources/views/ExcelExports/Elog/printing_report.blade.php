<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Краткий отчет печати</title>
</head>
<body>

<br>
<table>
    <tr>
        <th colspan="2" style="text-align: center">
            <b>Краткий отчет печати c {{ $work_start }} по {{ $work_finish }}</b>
        </th>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center"><b>ПОКАЗАТЕЛИ</b></td>
    </tr>
    <tr>
        <td>Количество уникальных карт</td>
        <td>{{ $data['uniqueTknCount'] }}</td>
    </tr>
    <tr>
        <th>Факт материала</th>
        <td title="Факт материала">{{ round($data['mat1count'], 2) }}</td>
    </tr>
    <tr>
        <th>Ширина, среднее</th>
        <td title="Ширина">{{ round($data['width1'], 2) }}</td>
    </tr>
    <tr>
        <th>Толщина, среднее</th>
        <td title="Толщина">{{ round($data['thickness1'], 2) }}</td>
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
        <th>Краски, среднее</th>
        <td title="Краски">{{ round($data['colors'], 2) }}</td>
    </tr>
    <tr>
        <th>Скорость печати, среднее</th>
        <td title="Скорость печати">{{ round($data['speed'], 2) }}</td>
    </tr>
    <tr>
        <th>Приправка кг</th>
        <td title="Приправка кг">{{ round($data['prepare_mass'], 2) }}</td>
    </tr>
    <tr>
        <th>Отходы печать</th>
        <td title="Отходы печать">{{ round($data['waste_print'], 2) }}</td>
    </tr>
    <tr>
        <th>Отходы сырье</th>
        <td title="Отходы сырье">{{ round($data['waste_raw'], 2) }}</td>
    </tr>
    <tr>
        <th>Отходы итог</th>
        <td title="Отходы итог">{{ round($data['waste_sum'], 2) }}</td>
    </tr>

    <tr>
        <th colspan="2" style="text-align: center"><b>ПРОСТОИ</b></th>
    </tr>

    <tr>
        <th>Приправка час</th>
        <td title="Приправка час">{{ round($data['prepare_hours'], 2) }}</td>
    </tr>
    <tr>
        <th>Коррекция PN</th>
        <td title="Коррекция PN">{{ round($data['correction_PN'], 2) }}</td>
    </tr>
    <tr>
        <th>Коррекция CMYK</th>
        <td title="Коррекция CMYK">{{ round($data['correction_CMYK'], 2) }}</td>
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
        <th>Переклейка форм</th>
        <td title="Переклейка форм">{{ round($data['form_glue'], 2) }}</td>
    </tr>
    <tr>
        <th>Замена расходных материалов</th>
        <td title="Замена расходных материалов">{{ round($data['service_replacement'], 2) }}</td>
    </tr>
    <tr>
        <th>Техническое обслуживание</th>
        <td title="Техническое обслуживание">{{ round($data['tech_service'], 2) }}</td>
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
        <th>Краткосрочный простой</th>
        <td title="Краткосрочный простой">{{ round($data['short_downtime'], 2) }}</td>
    </tr>
    <tr>
        <th>Простой без причины</th>
        <td title="Простой без причины">{{ round($data['no_reason_downtime'], 2) }}</td>
    </tr>
    <tr>
        <th>Сумма по простоям</th>
        <td title="Итого простой">{{ round($data['idles'], 2) }}</td>
    </tr>
    <tr>
        <th>Простой по Энкосту</th>
        <td title="Итого простой">{{ round($data['total_downtime'], 2) }}</td>
    </tr>
    <tr>
        <th>Время выпуска по Энкосту</th>
        <td title="Время выпуска по Энкосту">{{ round($data['work_productive'], 2) }}</td>
    </tr>
</table>

</body>
</html>
