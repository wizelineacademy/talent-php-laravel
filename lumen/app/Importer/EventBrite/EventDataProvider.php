<?php

namespace App\Importer\EventBrite;

use App\Importer\Contracts\EventDataProvider as DataProvider;
use GuzzleHttp\Client;

class EventDataProvider implements DataProvider {

    protected $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function getByLocation(string $location, int $page = 1) {
        $response = $this->client->get('events/search', [
            'query' => [
                'location.address' => $location,
                'page' => $page
            ]
        ]);

        $responseData = json_decode($response->getBody()->getContents());


        $items = array_map(function ($event) {
            $newEvent = [
                'external_id' => data_get($event, 'id'),
                'source' => 'eventbrite',
                'name' => data_get($event, 'name.text'),
                'description' => data_get($event, 'description.text'),
                'url' => data_get($event, 'url'),
                'start' => data_get($event, 'start.utc'),
                'end' => data_get($event, 'end.utc'),
                'image_url' => data_get($event, 'logo.original.url'),
            ];

            data_set($newEvent, 'metadata.venue_id', data_get($event, 'venue_id'));
            return $newEvent;
        }, $responseData->events);

        return (object) [
            'hasMore' => data_get($responseData, 'pagination.has_more_items'),
            'items' => $items
        ];
    }
}