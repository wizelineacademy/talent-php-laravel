<?php

use MongoDB\Client as MongoClient;
use App\Importer\Contracts\EventDataProvider;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/events', function (MongoClient $client) {
    $eventStorage = $client->test->events;
    $pipeline = [
        [
            '$graphLookup' => [
                "from"=> 'venues',
                "connectFromField"=> 'metadata.venue_id',
                "connectToField"=> 'external_id',
                "as"=> 'venue',
                "startWith"=> '$metadata.venue_id',
            ],
           
        ],
        ['$unwind'=>'$venue'],
    ];
    
    $cursor = $eventStorage->aggregate($pipeline);
    $items = $cursor->toArray();

    $formated = [
        'total'=> count($items),
        'last_page'=> 4,
        'current_page'=> 2,
        'size'=> 50,
        'items'=> $items,
    ];

    return response()->json($formated);
});

$router->get('/eventbrite', function (EventDataProvider $provider) {
    return $provider->getByLocation('Guadalajara');
});
