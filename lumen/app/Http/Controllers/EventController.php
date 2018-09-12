<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use MongoDB\Client as MongoClient;
use Illuminate\Pagination\LengthAwarePaginator;

class EventController extends Controller {
  public function index (Request $request, MongoClient $client){
    $page = $request->query('page', 1);  
    $size = $request->query('size', 25);

    $eventStorage = $client->test->events;
    $cursor = $eventStorage->find();
    $events = $cursor->toArray();

    $paginated = new LengthAwarePaginator($events, count($events), $size, $page);
    $paginated->setPath($request->getHttpHost()."/events?size=$size");

    return response()->json($paginated);
  }
}