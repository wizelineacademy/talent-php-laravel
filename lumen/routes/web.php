<?php

use App\Event;
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

$router->get('/events', function () {
    $events = Event::paginate(10);

    return response()->json($events);
});

$router->get('/eventbrite', function (EventDataProvider $provider) {
    return $provider->getByLocation('Guadalajara');
});
