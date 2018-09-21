<?php

namespace App\Importer\EventBrite;

use App\Importer\Contracts\EventDataProvider as DataProvider;
use GuzzleHttp\Client;

class EventDataProvider implements DataProvider {

    protected $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function getByLocation(string $location, $page) {
        $response = $this->client->get('events/search', [
            'query' => [
                'location.address' => $location,
                'page' => $page
            ]
        ]);

        $responseData = json_decode($response->getBody()->getContents());

        $events = array_map(function ($event) {
            $newEvent = [
                'external_id' => data_get($event, 'id'),
                'name' => data_get($event, 'name.text'),
                'description' => data_get($event, 'description.text'),
                'url' => data_get($event, 'url'),
                'start' => data_get($event, 'start.utc'),
                'end' => data_get($event, 'end.utc'),
            ];

            data_set($newEvent, 'metadata.venue_id', data_get($event, 'venue_id'));

            return $newEvent;
        }, $responseData->events);

        $result = [
            'pagination' => $responseData->pagination,
            'events' => $events
        ];

        return $result;
    }
}