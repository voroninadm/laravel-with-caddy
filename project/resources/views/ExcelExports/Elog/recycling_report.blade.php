<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Краткий отчет переработка</title>
</head>
<body>

<p>Краткий отчет переработки c {{ $work_start }} по {{ $work_finish }}</p>
<br>
<table>
    <tr>
        <td>Выработка гранулята</td>
        <td>{{ round($data['workout_mass'], 2) }}</td>
    </tr>
    <tr>
        <td>Масса литников</td>
        <td>{{ round($data['waste_mass'], 2) }}</td>
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
        <td>Замена ножей</td>
        <td>{{ round($data['change_knifes'], 2) }}</td>
    </tr>
    <tr>
        <td>Замена резцов</td>
        <td>{{ round($data['change_cutter'], 2) }}</td>
    </tr>
    <tr>
        <td>Чистка машины</td>
        <td>{{ round($data['tech_clean'], 2) }}</td>
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
