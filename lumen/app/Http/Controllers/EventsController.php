<?php

namespace App\Http\Controllers;

use MongoDB\Client as MongoClient;
use Illuminate\Http\Request;


 class EventsController extends Controller {

     private $client;

     public function __construct(MongoClient $mongoClient) {
        $this->client = $mongoClient;
    }

     public function fetch(Request $request) {
        $page = intval($request->get('page', 1));
        $size = intval($request->get('size', 10));

        $eventStorage = $this->client->test->events;

        $cursor   = $eventStorage->find([], [
            'skip'  => (($page - 1) * $size),
            'limit' => $size
        ]);

        $total        = $eventStorage->countDocuments();
        $lastPage     = ceil($total/$size);
        $items        = $cursor->toArray();
         return response()->json([
            'total'        => $total,
            'last_page'    => $lastPage,
            'current_page' => $page,
            'size'         => $size,
            'items'        => $items
        ]);
    }
} 