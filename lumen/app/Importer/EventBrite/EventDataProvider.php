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

        return json_decode($response->getBody()->getContents());
    }
}