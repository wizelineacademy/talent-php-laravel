<?php

return [
    'redis' => [
        'client'  => 'predis',
        'default' => [
            'host'     => env('REDIS_HOST'),
            'password' => null,
            'port'     => 6379,
            'database' => 0
        ]
    ],
    'mongodb' => [
        'host' => env('MONGODB_HOST', '127.0.0.1')
    ],
    'eventbrite' => [
        'token' => env('EB_TOKEN'),
        'base_uri' => env('EB_BASE_URI')
    ]
];