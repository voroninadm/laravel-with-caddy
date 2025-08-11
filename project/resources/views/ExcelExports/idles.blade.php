@php
    $totals = [
        'prepare_hours' => 0,
        'correction_PN' => 0,
        'correction_CMYK' => 0,
        'form_glue' => 0,
        'service_replacement' => 0,
        'electro' => 0,
        'mechanical' => 0,
        'electronics' => 0,
        'tech_service' => 0,

        'tech_clean' => 0,
        'change_glue' => 0,

        'calibrating' => 0,
        'reconfig' => 0,
        'change_teflon' => 0,
        'knifes_barbell' => 0,
        'change_net' => 0,
        'change_raw' => 0,
        'change_order' => 0,
        'change_knifes' => 0,
        'change_cutter' => 0,
        'wash' => 0,
        'clean_machine' => 0,

        'no_human' => 0,
        'no_work' => 0,
        'no_raw' => 0,
        'short_downtime' => 0,
        'no_reason_downtime' => 0,
        'sum' => 0,
        'total_downtime' => 0,
        'work_productive' => 0
    ];
@endphp

    <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчет по простоям</title>
</head>
<body>

<p>Отчет по простоям c {!! $work_start !!} по {!! $work_finish !!}</p>
<br>
<table>
    <thead>
    <tr>
        <th>Машина</th>
        <th>Электрика</th>
        <th>Механика</th>
        <th>Приправка</th>
        <th>Корректировка PN</th>
        <th>Корректировка CMYK</th>
        <th>Замена расходников</th>
        <th>Работа с формами</th>
        <th>Тех. обслуживание</th>

        <th>Электроника</th>
        <th>Наладка</th>
        <th>Перестройка</th>
        <th>Замена тефлона</th>
        <th>Замена ножей/штанги</th>
        <th>Замена сеток</th>
        <th>Замена сырья</th>
        <th>Замена заказа</th>
        <th>Замена ножей</th>
        <th>Замена резцов</th>
        <th>Замена клея</th>
        <th>Мойка машин</th>
        <th>Чистка машин/ техчистка</th>

        <th>Персонал</th>
        <th>Заказы</th>
        <th>Сырье</th>

        <th>Краткосрочный простой</th>
        <th>Простой без причины</th>
        <th>Сумма по простоям</th>
        <th>Простои по Энкосту</th>
        <th>Работа по Энкосту</th>
    </tr>
    </thead>
    @foreach($data as $machines)
        @foreach($machines as $machine)
            @php
                // Накапливаем суммы
                foreach($totals as $key => &$value) {
                    $value += floatval($machine[$key] ?? 0);
                }
            @endphp
            <tr>
                <td>{!! $machine['machine_name'] !!}</td>

                <td>{{ isset($machine['electro']) && $machine['electro'] > 0 ? $machine['electro'] : '' }}</td>
                <td>{{ isset($machine['mechanical']) && $machine['mechanical'] > 0 ? $machine['mechanical'] : '' }}</td>
                <td>{{ isset($machine['prepare_hours']) && $machine['prepare_hours'] > 0 ? $machine['prepare_hours'] : '' }}</td>
                <td>{{ isset($machine['correction_PN']) && $machine['correction_PN'] > 0 ? $machine['correction_PN'] : '' }}</td>
                <td>{{ isset($machine['correction_CMYK']) && $machine['correction_CMYK'] > 0 ? $machine['correction_CMYK'] : '' }}</td>
                <td>{{ isset($machine['service_replacement']) && $machine['service_replacement'] > 0 ? $machine['service_replacement'] : '' }}</td>
                <td>{{ isset($machine['form_glue']) && $machine['form_glue'] > 0 ? $machine['form_glue'] : '' }}</td>
                <td>{{ isset($machine['tech_service']) && $machine['tech_service'] > 0 ? $machine['tech_service'] : '' }}</td>
                <td>{{ isset($machine['electronics']) && $machine['electronics'] > 0 ? $machine['electronics'] : '' }}</td>
                <td>{{ isset($machine['calibrating']) && $machine['calibrating'] > 0 ? $machine['calibrating'] : '' }}</td>
                <td>{{ isset($machine['reconfig']) && $machine['reconfig'] > 0 ? $machine['reconfig'] : '' }}</td>
                <td>{{ isset($machine['change_teflon']) && $machine['change_teflon'] > 0 ? $machine['change_teflon'] : '' }}</td>
                <td>{{ isset($machine['knifes_barbell']) && $machine['knifes_barbell'] > 0 ? $machine['knifes_barbell'] : '' }}</td>
                <td>{{ isset($machine['change_net']) && $machine['change_net'] > 0 ? $machine['change_net'] : '' }}</td>
                <td>{{ isset($machine['change_raw']) && $machine['change_raw'] > 0 ? $machine['change_raw'] : '' }}</td>
                <td>{{ isset($machine['change_order']) && $machine['change_order'] > 0 ? $machine['change_order'] : '' }}</td>
                <td>{{ isset($machine['change_knifes']) && $machine['change_knifes'] > 0 ? $machine['change_knifes'] : '' }}</td>
                <td>{{ isset($machine['change_cutter']) && $machine['change_cutter'] > 0 ? $machine['change_cutter'] : '' }}</td>
                <td>{{ isset($machine['change_glue']) && $machine['change_glue'] > 0 ? $machine['change_glue'] : '' }}</td>
                <td>{{ isset($machine['wash']) && $machine['wash'] > 0 ? $machine['wash'] : '' }}</td>
                <td>{{ isset($machine['clean_machine']) && $machine['clean_machine'] > 0 ? $machine['clean_machine'] : '' }}
                    {{ isset($machine['tech_clean']) ?    '/' : '' }}
                    {{ isset($machine['tech_clean']) && $machine['tech_clean'] > 0 ? $machine['tech_clean'] : '' }}
                </td>
                <td>{{ isset($machine['no_human']) && $machine['no_human'] > 0 ? $machine['no_human'] : '' }}</td>
                <td>{{ isset($machine['no_work']) && $machine['no_work'] > 0 ? $machine['no_work'] : '' }}</td>
                <td>{{ isset($machine['no_raw']) && $machine['no_raw'] > 0 ? $machine['no_raw'] : '' }}</td>
                <td>{{ isset($machine['short_downtime']) && $machine['short_downtime'] > 0 ? $machine['short_downtime'] : '' }}</td>
                <td>{{ isset($machine['no_reason_downtime']) && $machine['no_reason_downtime'] > 0 ? $machine['no_reason_downtime'] : '' }}</td>

                <td>{!! $machine['sum'] ?? '' !!}</td>
                <td>{!! $machine['total_downtime'] ?? '' !!}</td>
                <td>{!! $machine['work_productive'] ?? '' !!}</td>
            </tr>
        @endforeach
    @endforeach
    <tr>
        <td><strong>Итого</strong></td>
        <td>{!! $totals['electro'] != 0 ? $totals['electro'] : '' !!} </td>
        <td>{!! $totals['mechanical'] != 0 ? $totals['mechanical'] : '' !!}</td>
        <td>{!! $totals['prepare_hours'] != 0 ? $totals['prepare_hours'] : '' !!}</td>
        <td>{!! $totals['correction_PN'] != 0 ? $totals['correction_PN'] : '' !!}</td>
        <td>{!! $totals['correction_CMYK'] != 0 ? $totals['correction_CMYK'] : '' !!}</td>
        <td>{!! $totals['service_replacement'] != 0 ? $totals['service_replacement'] : '' !!}</td>
        <td>{!! $totals['form_glue'] != 0 ? $totals['form_glue'] : '' !!}</td>
        <td>{!! $totals['tech_service'] != 0 ? $totals['tech_service'] : '' !!}</td>
        <td>{!! $totals['electronics'] != 0 ? $totals['electronics'] : '' !!}</td>
        <td>{!! $totals['calibrating'] != 0 ? $totals['calibrating'] : '' !!}</td>
        <td>{!! $totals['reconfig'] != 0 ? $totals['reconfig'] : '' !!}</td>
        <td>{!! $totals['change_teflon'] != 0 ? $totals['change_teflon'] : '' !!}</td>
        <td>{!! $totals['knifes_barbell'] != 0 ? $totals['knifes_barbell'] : '' !!}</td>
        <td>{!! $totals['change_net'] != 0 ? $totals['change_net'] : '' !!}</td>
        <td>{!! $totals['change_raw'] != 0 ? $totals['change_raw'] : '' !!}</td>
        <td>{!! $totals['change_order'] != 0 ? $totals['change_order'] : '' !!}</td>
        <td>{!! $totals['change_knifes'] != 0 ? $totals['change_knifes'] : '' !!}</td>
        <td>{!! $totals['change_cutter'] != 0 ? $totals['change_cutter'] : '' !!}</td>
        <td>{!! $totals['change_glue'] != 0 ? $totals['change_glue'] : '' !!}</td>
        <td>{!! $totals['wash'] != 0 ? $totals['wash'] : '' !!}</td>
        <td>{!! $totals['clean_machine'] != 0 ? $totals['clean_machine'] : 0 !!}
            / {!! $totals['tech_clean'] != 0 ? $totals['tech_clean'] : 0 !!} </td>
        <td>{!! $totals['no_human'] != 0 ? $totals['no_human'] : '' !!}</td>
        <td>{!! $totals['no_work'] != 0 ? $totals['no_work'] : '' !!}</td>
        <td>{!! $totals['no_raw'] != 0 ? $totals['no_raw'] : '' !!}</td>
        <td>{!! $totals['short_downtime'] != 0 ? $totals['short_downtime'] : '' !!}</td>
        <td>{!! $totals['no_reason_downtime'] != 0 ? $totals['no_reason_downtime'] : '' !!}</td>
        <td>{!! $totals['sum'] != 0 ? $totals['sum'] : '' !!}</td>
        <td> {!! $totals['total_downtime'] != 0 ? $totals['total_downtime'] : '' !!}</td>
        <td>{!! $totals['work_productive'] != 0 ? $totals['work_productive'] : '' !!}</td>
    </tr>
</table>

</body>
</html>
