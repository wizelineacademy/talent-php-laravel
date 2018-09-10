<?php

return [
    'default' => env('DB_CONNECTION', 'mongodb'),

    'redis' => [
        'client' => 'predis',
        'default' => [
          'host' => env('REDIS_HOST', '127.0.0.1'),
          'password' => env('REDIS_PASSWORD', null),
          'port' => env('REDIS_PORT', 6379),
          'database' => 0
        ]
    ],

    'connections' => [
        'mongodb' => [
            'driver'   => 'mongodb',
            'host'     => env('DB_HOST', 'localhost'),
            'port'     => env('DB_PORT', 27017),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'options'  => [
                'database' => 'admin' // sets the authentication database required by mongo 3
            ]
        ],
    ]
];