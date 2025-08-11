<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {!! $spoolcutter !!}</title>
</head>
<body>

<p>Выработка {!! $spoolcutter !!} </p>
<p>c {!! $work_start !!} по {!! $work_finish !!}</p>
<br>
<table>
    <thead>
    <tr>
        <th>Ширина</th>
        <th>Количество</th>
    </tr>
    </thead>
    @foreach($spoolcutter_workout as $width => $count)
        <tr>
            <td>{!! $width !!}</td>
            <td>{!! $count !!}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
