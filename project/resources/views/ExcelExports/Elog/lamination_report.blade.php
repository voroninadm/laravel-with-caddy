<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Краткий отчет ламинация</title>
</head>
<body>

<br>
<table>
    <tr>
        <th colspan="2" style="text-align: center">
            <b>Краткий отчет ламинации c {{ $work_start }} по {{ $work_finish }}</b>
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
        <th>Факт материала-1</th>
        <td title="Факт материала">{{ round($data['mat1count'], 2) }}</td>
    </tr>
    <tr>
        <th>Ширина-1, среднее</th>
        <td title="Ширина">{{ round($data['width1'], 2) }}</td>
    </tr>
    <tr>
        <th>Толщина-1, среднее</th>
        <td title="Толщина">{{ round($data['thickness1'], 2) }}</td>
    </tr>
    <tr>
        <th>Факт материала-2</th>
        <td title="Факт материала-2">{{ round($data['mat2count'], 2) }}</td>
    </tr>
    <tr>
        <th>Ширина-2, среднее</th>
        <td title="Ширина-2">{{ round($data['width2'], 2) }}</td>
    </tr>
    <tr>
        <th>Толщина-2, среднее</th>
        <td title="Толщина-2">{{ round($data['thickness2'], 2) }}</td>
    </tr>
    <tr>
        <th>Факт покрытия</th>
        <td title="Факт покрытия">{{ round($data['covering_count'], 2) }}</td>
    </tr>
    <tr>
        <th>Ширина покрытия, среднее</th>
        <td title="Ширина покрытия">{{ round($data['covering_width'], 2) }}</td>
    </tr>
    <tr>
        <th>Толщина покрытия, среднее</th>
        <td title="Толщина покрытия">{{ round($data['covering_thickness'], 2) }}</td>
    </tr>
    <tr>
        <th>Выработка кг
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
        <th>Отходы печать</th>
        <td title="Отходы печать">{{ round($data['waste_print'], 2) }}</td>
    </tr>
    <tr>
        <th>Отходы ламинация</th>
        <td title="Отходы ламинация">{{ round($data['waste_lam'], 2) }}</td>
    </tr>
    <tr>
        <th>Отходы итог</th>
        <td title="Отходы итог">{{ round($data['waste_sum'], 2) }}</td>
    </tr>

    <tr>
        <th colspan="2" style="text-align: center"><b>ПРОСТОИ</b></th>
    </tr>

    <tr>
        <th>Приправка</th>
        <td title="Приправка">{{ round($data['prepare_hours'], 2) }}</td>
    </tr>
    <tr>
        <th>Техчистка</th>
        <td title="Техчистка">{{ round($data['tech_clean'], 2) }}</td>
    </tr>
    <tr>
        <th>Замена клея</th>
        <td title="Замена клея">{{ round($data['change_glue'], 2) }}</td>
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
        <th>Техническое обслуживание</th>
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
