<?php

use Carbon\Carbon;

function getYearsWord($age): string
{
    if ($age % 10 == 1 && $age % 100 != 11) {
        return 'год';
    } elseif ($age % 10 >= 2 && $age % 10 <= 4 && ($age % 100 < 10 || $age % 100 >= 20)) {
        return 'года';
    } else {
        return 'лет';
    }
}
?>

    <!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результаты HR-тестирования</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="{{ $isDownloadable ? 'text-sm tracking-tight leading-5' : 'text-md'}}">

<h1 class="hidden">Анекта соискателя</h1>

<div class="px-2 py-5 max-w-2xl mx-auto">
    <div class="flex gap-5 items-center justify-between text-center py-3">
        <img src="{{ $isDownloadable ? public_path('img/logo.svg') : asset('img/logo.svg') }}"
             alt="logo">
        <div class="flex flex-col text-gray-800">
            <p class="text-lg font-bold tracking-tight">
                Анкета соискателя
                <span>от {{ Carbon::parse($created_at)->format('d.m.Y') }}</span>
            </p>
            <span class="text-gray-600">На вакансию: {{ $about['vacancy']  }}</span>

        </div>
    </div>

    <div class="flex flex-col items-center py-5">
        <p class="text-center font-bold text-gray-800 text-xl py-6 tracking-tight">{{ $about['last_name'] }} {{$about['first_name']}} {{$about['middle_name']}}</p>
        <p>
            <span class="font-bold text-gray-800">Дата рождения:</span>
            {{ Carbon::parse($about['birth_date'])->format('d.m.Y') }},
            <span>{{ Carbon::parse($about['birth_date'])->age}} {{getYearsWord(Carbon::parse($about['birth_date'])->age)}}.</span>
        </p>
        <p>
            <span class="font-bold text-gray-800">Пол:</span> {{ $about['sex'] }}.
            <span class="font-bold text-gray-800">Гражданство:</span> {{ $about['nationality'] }}.</p>
        <p>
            <span class="font-bold text-gray-800">Место проживания: </span> {{ $about['address'] }}
        </p>
        <p>
            <span class="font-bold text-gray-800">Контактный номер телефона: </span> {{ $about['phone'] }}
        </p>
    </div>

    <div class="flex flex-col gap-2 py-3">
        <p>
            <span class="font-bold text-gray-800">Образование: </span> {{ $about['education'] }}.
            @if($about['speciality'])
                <span
                    class="font-bold text-gray-800">Специальность по диплому: </span> {{ $about['speciality'] }}.
            @endif
        </p>

        <p><span
                class="font-bold text-gray-800">Знание иностранных языков: </span>{{ $about['languages'] ?: 'Не указано' }}
            .
        </p>

        <p>
            <span class="font-bold text-gray-800">Семейное положение: </span> {{ $about['marital_status'] }}
            . {{ $about['children'] === "Да" ? 'Есть несовершеннолетние дети' : 'Нет несовершеннолетних детей' }}.
        </p>
        <p>
             <span
                 class="font-bold text-gray-800">{{ $about['narcotics'] === 'Нет' ? 'Не дает согласия' : 'Дает согласие' }}</span>
            на прохождение медицинского теста, определяющего употребление наркотических веществ и/или алкоголя.
        </p>
        <p>
            <span
                class="font-bold text-gray-800"> {{ $about['criminal'] === 'Нет' ? 'Не было привлечений' : 'Были привлечения' }} </span>
            к уголовной ответственности.
        </p>
        <p>
            <span
                class="font-bold text-gray-800">{{ $about['contract'] === 'Нет' ? 'Не состоял(а)' : 'Состоял(а)' }}</span>
            на государственной службе или службе по контракту за последние 2 года.
        </p>
        <p>
            <span class="font-bold text-gray-800">{{ $about['trips'] === 'Нет' ? 'Не готов(а)' : 'Готов(а)' }}</span>
            к командировкам.
            Водительские права категории В - <span
                class="font-bold text-gray-800">{{ $about['driver'] === 'Нет' ? 'отсутствуют' : 'в наличии ' }}</span>.
        </p>

        <p>
            <span class="font-bold text-gray-800">Хобби и увлечения: </span> {{ $about['hobby'] ?: 'Не указаны' }}.
        </p>
        <p>
            <span class="font-bold text-gray-800">Прежнее место работы: </span> {{ $about['previous_workplace']  }}.
            <span
                class="font-bold text-gray-800">Указанный стаж на прежнем месте работы: </span> {{ $about['previous_workplace_long']  }}
            .

        </p>

        <p>Источником информации указано: "{{ $about['info_source'] }}".
            Указанный уровень заработной платы, соответствующий профессиональной компетенции, указан в
            размере {{ $about['salary'] }} тысяч рублей.
            Минимальный уровень заработной платы указан в размере {{ $about['salary_min'] }} тысяч рублей.
        </p>
        <p>
            <span
                class="font-bold text-gray-800">Готовность приступить к работе, начиная с </span> {{ Carbon::parse($about['date_workout'])->format('d.m.Y') }}
        </p>
        <hr>
    </div>

</div>

@pageBreak

<div class="px-2 py-5 max-w-2xl mx-auto flex flex-col gap-10">
    <div class="flex gap-5 items-center justify-between text-center py-3">
        <img src="{{ $isDownloadable ? public_path('img/logo.svg') :asset('img/logo.svg') }}"
             alt="logo">
        <div class="flex flex-col text-gray-800">
            <h1 class="text-lg font-bold ">
                Результаты тестирования
            </h1>
            <span>от {{ Carbon::parse($created_at)->format('d.m.Y') }}</span>
        </div>
    </div>
    <div class="relative">
        <img
            src="{{ $isDownloadable ? public_path('img/cattellTable.webp') : asset('img/cattellTable.webp') }}"
            alt="Таблица">
        <svg id="oSvg1" viewBox="0 0 900 656" class="absolute top-0">
            <line x1="{{ $test_results['A'] }}" y1="41" x2="{{ $test_results['B'] }}" y2="71" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['B'] }}" y1="71" x2="{{ $test_results['C'] }}" y2="101" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['C'] }}" y1="101" x2="{{ $test_results['E'] }}" y2="131" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['E'] }}" y1="131" x2="{{ $test_results['F'] }}" y2="161" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['F'] }}" y1="161" x2="{{ $test_results['G'] }}" y2="191" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['G'] }}" y1="191" x2="{{ $test_results['H'] }}" y2="221" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['H'] }}" y1="221" x2="{{ $test_results['I'] }}" y2="251" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['I'] }}" y1="251" x2="{{ $test_results['L'] }}" y2="281" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['L'] }}" y1="281" x2="{{ $test_results['M'] }}" y2="311" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['M'] }}" y1="311" x2="{{ $test_results['N'] }}" y2="341" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['N'] }}" y1="341" x2="{{ $test_results['O'] }}" y2="371" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['O'] }}" y1="371" x2="{{ $test_results['Q1'] }}" y2="401" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['Q1'] }}" y1="401" x2="{{ $test_results['Q2'] }}" y2="431" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['Q2'] }}" y1="431" x2="{{ $test_results['Q3'] }}" y2="461" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['Q3'] }}" y1="461" x2="{{ $test_results['Q4'] }}" y2="491" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['Q4'] }}" y1="491" x2="{{ $test_results['F1'] }}" y2="521" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['F1'] }}" y1="521" x2="{{ $test_results['F2'] }}" y2="551" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['F2'] }}" y1="551" x2="{{ $test_results['F3'] }}" y2="581" stroke="black"
                  stroke-width="3"></line>
            <line x1="{{ $test_results['F3'] }}" y1="581" x2="{{ $test_results['F4'] }}" y2="611" stroke="black"
                  stroke-width="3"></line>

            <circle cx="{{ $test_results['A'] }}" cy="41" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['B'] }}" cy="71" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['C'] }}" cy="101" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['E'] }}" cy="131" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['F'] }}" cy="161" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['G'] }}" cy="191" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['H'] }}" cy="221" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['I'] }}" cy="251" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['L'] }}" cy="281" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['M'] }}" cy="311" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['N'] }}" cy="341" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['O'] }}" cy="371" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['Q1'] }}" cy="401" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['Q2'] }}" cy="431" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['Q3'] }}" cy="461" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['Q4'] }}" cy="491" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['F1'] }}" cy="521" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['F2'] }}" cy="551" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['F3'] }}" cy="581" fill="black" r="5" stroke="white"></circle>
            <circle cx="{{ $test_results['F4'] }}" cy="611" fill="black" r="5" stroke="white"></circle>
        </svg>
    </div>

    <hr>

    <div class="flex flex-col gap-3">
        <h3 class="text-center font-lg font-bold text-gray-800">Уровень самооценки</h3>
        <div class="MD text-gray-400 text-xs" style="display:grid; grid-template-columns: repeat(14, 1fr);">
            <span>1</span>
            <span>2</span>
            <span>3</span>
            <span>4</span>
            <span>5</span>
            <span>6</span>
            <span>7</span>
            <span>8</span>
            <span>9</span>
            <span>10</span>
            <span>11</span>
            <span>12</span>
            <span>13</span>
            <span>14</span>
        </div>

        <svg width="100%" height="25" viewBox="0 0 629 25" preserveAspectRatio="none">
            <defs>
                <pattern id="pg21" x="0" y="0" width="21" height="25" patternUnits="userSpaceOnUse">
                    <rect x="0" y="0" width="20" height="25" stroke-width="0" fill="whitesmoke"></rect>
                </pattern>
                <pattern id="pa21" x="0" y="0" width="21" height="25" patternUnits="userSpaceOnUse">
                    <rect x="0" y="0" width="20" height="25" stroke-width="0" fill="green"></rect>
                </pattern>
            </defs>
            <rect x="0" y="0" width="629" height="25" stroke-width="0" fill="url(#pg21)"></rect>
            <rect x="0" y="0" width="{{ $test_results['MD'] * 41 }}" height="25" stroke-width="0"
                  fill="url(#pa21)"></rect>
        </svg>

        <div class="text-sm text-gray-800">
            <p>
                <span class="font-bold">0 - 4</span> - Недовольство собой, неуверенность в себе, излишняя критичность по
                отношению к себе.
            </p>
            <p><span class="font-bold">5 - 9</span> - Адекватность самооценки, определенная зрелость личности</p>
            <p><span class="font-bold">10 - 14</span> - Переоценка своих возможностей, самоуверенность и довольство
                собой.</p>
        </div>
    </div>
</div>

</body>
</html>
