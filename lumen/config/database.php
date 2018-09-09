<?php

return [
    'redis' => [

        'cluster' => false,
    
        'default' => [
            'host'     => env('REDIS_HOST', '127.0.0.1'),
            'port'     => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DATABASE', 0),
        ],
    
    ],
];