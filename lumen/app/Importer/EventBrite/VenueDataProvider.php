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
        $response = $this->client->get("venues/$id/");

        $responseData = json_decode($response->getBody()->getContents());

        return [
            'external_id' => $id,
            'source' => 'eventbrite',
            'name' => $responseData->name,
            'address' => $responseData->address->localized_address_display,
        ];
    }
}