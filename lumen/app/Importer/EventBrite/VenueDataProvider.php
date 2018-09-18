<?php

namespace App\Importer\EventBrite;

use App\Importer\Contracts\VenueDataProvider as DataProvider;
use GuzzleHttp\Client;

class VenueDataProvider implements DataProvider {

    protected $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function get(string $id) {
        $response = $this->client->get("venues/$id");

        $responseData = json_decode($response->getBody()->getContents());

        $newEvent = [
            'external_id' => $id,
            'source' => 'eventbrite',
            'name' => data_get($responseData, 'name'),
            'address' => data_get($responseData, 'address.address_1') . ', '
                . data_get($responseData, 'address.address_') . ', '
                . data_get($responseData, 'address.city') . ', '
                . data_get($responseData, 'address.region') . ', '
                . data_get($responseData, 'address.postal_code')
        ];

        return $newEvent;
    }
}