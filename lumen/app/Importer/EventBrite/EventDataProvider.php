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

        return array_map(function ($event) {
            return [
                'name' => data_get($event, 'name.text'),
                'description' => data_get($event, 'description.text'),
                'url' => data_get($event, 'url'),
                'start' => data_get($event, 'start.utc'),
                'end' => data_get($event, 'end.utc')
            ];
        }, $responseData->events);
    }
}