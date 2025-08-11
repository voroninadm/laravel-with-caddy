<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultUsers = [
            [
                'name' => 'супер-админ',
                'login' => 'admin',
                'email' => 'voronin.adm@mail.ru',
                'password' =>  Hash::make('pass_word'),
            ],
            [
                'name' => 'Менеджер',
                'login' => 'manager',
                'email' => 'manager@example.example',
                'password' => Hash::make('pass_word'),
            ],
            [
                'name' => 'Мастер',
                'login' => 'master',
                'email' => 'master@example.example',
                'password' => Hash::make('pass_word'),
            ],
        ];

        $users = [
            [
                'name' => 'Ольга Зимницкая',
                'login' => 'Zimnitskaya',
                'job' => 'Самый добрый начальник',
            ],
            [
                'name' => 'Алексей Потачёв',
                'login' => 'alexeip',
                'job' => 'Шеф',
            ],
            [
                'name' => 'Наталия Барнаулова',
                'login' => 'Nataliabr',
                'job' => 'Начальник форменного участка',
            ],
            [
                'name' => 'Мастер смены',
                'login' => 'masterUP',
                'job' => 'мастер смены',
            ],
            [
                'name' => 'Григорий Скирда',
                'login' => 'GrigoriySk',
                'job' => 'Специалист по бережливому производству',
            ],
            [
                'name' => 'Ирина Веселова',
                'login' => 'Irinaves',
                'job' => 'Начальник производства',
            ],
            [
                'name' => 'Сергей Талалаев',
                'login' => 'Sergeyt',
                'job' => 'Директор по логистике',
            ],
            [
                'name' => 'Светлана Сивкова',
                'login' => 'Svetlanas',
                'job' => 'Ведущий специалист ПСО',
            ],
            [
                'name' => 'Владимир Андрушко',
                'login' => 'vladimira',
                'job' => 'инженер  по сертификации',
            ],
            [
                'name' => 'Сергей Зинченко',
                'login' => 'sergeyzn',
                'job' => 'технолог участка печати',
            ],
            [
                'name' => 'Анастасия Сиротина',
                'login' => 'ans',
                'job' => 'распределитель работ',
            ],
            [
                'name' => 'Сергей Барнаулов',
                'login' => 'Sergeyb',
                'job' => 'Главный инженер',
            ],
            [
                'name' => 'Татьяна Баронина',
                'login' => 'tatyanab',
                'job' => 'Начальник технического отдела',
            ],
            [
                'name' => 'Татьяна Гришина',
                'login' => 'tatyanag',
                'job' => 'инженер ПСО',
            ],
            [
                'name' => 'Елизавета Абрамова',
                'login' => 'elizavetaa',
                'job' => 'инженер ПСО',
            ],
            [
                'name' => 'Вероника Сорх',
                'login' => 'sorh',
                'job' => 'инженер ПСО',
            ],
            [
                'name' => 'Анастасия Алексеева',
                'login' => 'anastasiyaa',
                'job' => 'инженер ПСО',
            ],
            [
                'name' => 'Зинаида Богданчикова',
                'login' => 'zinaidab',
                'job' => 'Начальник учебного центра',
            ],
            [
                'name' => 'Елена Фомина',
                'login' => 'elenafm',
                'job' => 'экономист',
            ],
            [
                'name' => 'Ольга Вострикова',
                'login' => 'olgav',
                'job' => 'инженер ПСО',
            ],
            [
                'name' => 'Светлана Поцко',
                'login' => 'AE47',
                'job' => 'технолог участка печати',
            ],
            [
                'name' => 'Андрей Целиков',
                'login' => 'AndreiT',
                'job' => 'Главный технолог',
            ],
            [
                'name' => 'Сергей Васильков',
                'login' => 'sv',
                'job' => 'Директор по качеству',
            ],
            [
                'name' => 'Учетчик участка печати',
                'login' => 'Uchet',
                'job' => 'учетчик участка печати',
            ],
            [
                'name' => 'Колорист',
                'login' => 'Color',
                'job' => 'колорист',
            ],
            [
                'name' => 'Монтажный участок',
                'login' => 'Montas',
                'job' => '',
            ],
            [
                'name' => 'Надежда Серова',
                'login' => 'nadegdas',
                'job' => '',
            ],
            [
                'name' => 'Лилия Халабарчук',
                'login' => 'Lv',
                'job' => 'начальник ОТК',
            ],
            [
                'name' => 'Наталья Трохина',
                'login' => 'Nt',
                'job' => 'специалист по рекламациям',
            ],
            [
                'name' => 'Максим Сапогов-Постолатий',
                'login' => 'MaksimSP',
                'job' => 'инженер',
            ],
            [
                'name' => 'Алексей Васильев',
                'login' => 'alekseyv',
                'job' => 'Технолог участка печати',
            ],
            [
                'name' => 'Дмитрий Логинов',
                'login' => 'Dmitry.Loginov',
                'job' => 'инженер наладчик',
            ],
            [
                'name' => 'Ирина Половинкина',
                'login' => 'Irinap',
                'job' => 'инженер ПСО',
            ],
            [
                'name' => 'Склад',
                'login' => 'sklad',
                'job' => 'директор по логистике',
            ],
            [
                'name' => 'Анастасия Сероштан',
                'login' => 'anastasiyasn',
                'job' => 'инженер ОТК',
            ]
        ];

        User::factory()->createMany($defaultUsers);
        User::factory()->createMany($users);
    }
}
