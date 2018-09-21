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
    $venueStorage = $client->test->venues;
    $cursorEvents = $eventStorage->find();
    $items = $cursorEvents->toArray();
    foreach($items as $event){
        $venueId = data_get($event, 'metadata.venue_id');
        $query = array('external_id' => $venueId);
        $cursorVenue = $venueStorage->find($query);
        $venue = $cursorVenue->toArray();
        if(!empty($venue)){
            data_set($event, 'venue.external_id', $venueId);
            data_set($event, 'venue.source', data_get($venue[0], 'source'));
            data_set($event, 'venue.name', data_get($venue[0], 'name'));
            data_set($event, 'venue.address', data_get($venue[0], 'address'));
        }
    }
    
    return response()->json($items);
});

$router->get('/eventbrite', function (EventDataProvider $provider) {
    return $provider->getByLocation('Guadalajara', 1);
});
