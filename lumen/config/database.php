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
    ]
];