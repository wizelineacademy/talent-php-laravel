<?php

namespace App\Http\Controllers;

use MongoDB\Client as MongoClient;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MongoClient $client)
    {
        $this->client = $client;
    }

    public function get(Request $request) {

        $page = (int)$request->get('page', 1);
        $size = (int)$request->get('size', 10);
        $skip  = ($page - 1) * $size;

        $eventStorage = $this->client->test->events;

        $totalItems = $eventStorage->count();
        $totalPages = ceil($totalItems / $size);

        $cursor = $eventStorage->find([], ['limit' => $size, 'skip' => $skip, 'sort'=> ["start"=> 1]] );
        $items = $cursor->toArray();

        $response = (object) [
            "total" => $totalItems,
            "lastPage" => $totalPages,
            "curentPage" => $page,
            "size" => $size,
            "items" => $items
        ];
        
        
        return response()->json($response);
    }
}
