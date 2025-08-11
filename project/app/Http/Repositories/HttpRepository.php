<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\ApiSpbInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class HttpRepository implements ApiSpbInterface
{
    public function getOrders(String $tkn): Response
    {
        return Http::get(config('custom.DB_GET_JSON_ORDER') . '?tk=' . $tkn);
    }

    public function getSchedule(): Response
    {
        return Http::get(config('custom.DB_GET_JSON_SCHEDULE'))->throw();
    }
}
