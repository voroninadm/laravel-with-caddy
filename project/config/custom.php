<?php

//  кастомные переменные из .env для прод-сборки с кэшированием конфига
return [
    'DB_GET_JSON_ORDER' => env('DB_GET_JSON_ORDER'),
    'DB_GET_JSON_SCHEDULE' => env('DB_GET_JSON_SCHEDULE'),

    'TELEGRAM_BOT_TOKEN' => env('TELEGRAM_BOT_TOKEN'),
    'TELEGRAM_CHAT_ID' => env('TELEGRAM_CHAT_ID'),

    'ENCOST_API' => env('ENCOST_API'),
    'ENCOST_TOKEN' => env('ENCOST_TOKEN'),
];
