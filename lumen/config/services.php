<?php

return [
    'mongodb' => [
        'host' => env('MONGODB_HOST', '127.0.0.1')
    ],
    'eventbrite' => [
        'token' => env('EB_TOKEN'),
        'base_uri' => env('EB_BASE_URI')
    ]
];