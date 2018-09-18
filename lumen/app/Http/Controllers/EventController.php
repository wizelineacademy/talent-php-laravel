<?php

namespace App\Http\Controllers;

use MongoDB\Client as MongoClient;
use Illuminate\Http\Request;

class EventController extends Controller {

    private $mongoClient;

    public function __construct(MongoClient $mongoClient) {
        $this->mongoClient = $mongoClient;
    }

    public function find(Request $request) {
        // Get Query Params
        $page = intval($request->get('page', 1));
        $size = intval($request->get('size', 5));


        // Get the Page and it's metadata
        $eventStorage = $this->mongoClient->test->events;
        $pageCursor   = $eventStorage->find([], [
            'skip'  => (($page - 1) * $size),
            'limit' => $size
        ]);
        $total        = $eventStorage->countDocuments();
        $lastPage     = ceil($total/$size);
        $items        = $pageCursor->toArray();

        return response()->json([
            'total'        => $total,
            'last_page'    => $lastPage,
            'current_page' => $page,
            'size'         => $size,
            'items'        => $items
        ]);
    }
}