<?php

namespace App\Importer\EventBrite;

use App\Importer\Contracts\VenueDataProvider as DataProvider;
use GuzzleHttp\Client;

class VenueDataProvider implements DataProvider {

    protected $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function getByID(string $id) {
        $response = $this->client->get('venues/'.$id);

        $responseData = json_decode($response->getBody()->getContents());
       
        $newVenue = [
            'name' => data_get($responseData, 'name'),
            'address' => data_get($responseData, 'address.localized_address_display'),
        ];

        data_set($newVenue, 'id', data_get($responseData, 'id'));

        return $newVenue;
    }

}