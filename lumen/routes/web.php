<?php

use MongoDB\Client as MongoClient;
use Illuminate\Http\Request;
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

$router->get('/events', function (MongoClient $client,Request $request) {
    $args  = $request->query->all();
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



    $spliced_array = array_slice($items, ($args['page']-1)*$args['size'],$args['size']);
    $formated = [
        'total'=> count($items),
        'last_page'=> ceil(count($items)/$args['size']),
        'current_page'=> intval($args['page']),
        'size'=> intval($args['size']),
        'items'=> $spliced_array ,
    ];

    return response()->json($formated);
});

$router->get('/eventbrite', function (EventDataProvider $provider) {
    return $provider->getByLocation('Guadalajara');
});
