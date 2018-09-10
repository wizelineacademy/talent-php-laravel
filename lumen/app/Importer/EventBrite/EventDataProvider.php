<?php

namespace App\Importer\EventBrite;

use App\Importer\Contracts\EventDataProvider as DataProvider;
use GuzzleHttp\Client;

class EventDataProvider implements DataProvider {

    protected $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function getByLocation(string $location) {
        $response = $this->client->get('events/search', [
            'query' => [
                'location.address' => $location
            ]
        ]);

        $responseData = json_decode($response->getBody()->getContents());

        
        return $this->parseEvents($responseData->events);
    }

    public function getPaginatedByLocation(string $location, int $page = 1): object {
        $response = $this->client->get('events/search', [
            'query' => [
                'location.address' => $location,
                'page' => $page
            ]
        ]);

        $responseData = json_decode($response->getBody()->getContents());

        $events = $this->parseEvents($responseData->events);

        return (object) [
            'pagination' => $responseData->pagination,
            'events' => $events
        ];
    }

    private function parseEvents(array $events): array {
        return array_map(function ($event) {
            return [
                'external_id' => data_get($event, 'id'),
                'name' => data_get($event, 'name.text'),
                'description' => data_get($event, 'description.text'),
                'url' => data_get($event, 'url'),
                'start' => data_get($event, 'start.utc'),
                'end' => data_get($event, 'end.utc'),
                'metadata' => [
                    'venue_id' => $event->venue_id
                ]
            ];
        }, $events);
    }
}