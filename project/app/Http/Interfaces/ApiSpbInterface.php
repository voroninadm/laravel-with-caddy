<?php

namespace App\Http\Interfaces;

use Illuminate\Http\Client\Response;

interface ApiSpbInterface
{
    /** Запрос к БД в СПб для получения данных по карте
     * @param String $tkn
     * @return Response
     */
    public function getOrders(String $tkn): Response;

    /** Запрос к БД в СПб для получения расписания
     * @return Response
     */
    public function  getSchedule(): Response;
}
