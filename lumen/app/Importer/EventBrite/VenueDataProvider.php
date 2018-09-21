<?php

namespace App\Importer\EventBrite;

use App\Importer\Contracts\VenueDataProvider as DataProvider;
use GuzzleHttp\Client;

class VenueDataProvider implements DataProvider {

    protected $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function getById(string $id) {
        $response = $this->client->get("venues/$id");

        $venue = json_decode($response->getBody()->getContents());

        $newVenue = [
            'external_id' => $id,
            'source' => 'eventbrite',
            'name' => data_get($venue, 'name.text'),
            'address' => data_get($venue, 'address.localized_address_display')
        ];

        return $newVenue;
    }
}