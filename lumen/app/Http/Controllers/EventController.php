<?php

namespace App\Http\Controllers;

class EventController extends Controller {
  public function index (\Illuminate\Http\Request $request){
      $size = (int) $request->query('per_page', 25);
      $events = \App\Event::paginate($size);
      $events = $events->toArray();
      $query = "&size={$size}";
      $events['first_page_url'] .= $query;
      $events['last_page_url'] .= $query;
      $events['next_page_url'] = $events['next_page_url'] ? $events['next_page_url'] . $query : null;
      $events['prev_page_url'] = $events['prev_page_url'] ? $events['prev_page_url'] . $query : null;
  
      return response()->json($events);
  }
}