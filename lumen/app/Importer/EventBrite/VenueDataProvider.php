<?php

use App\Importer\Contracts\EventDataProvider as DataProvider;
use GuzzleHttp\Client;

class EventDataProvider implements DataProvider {

    protected $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function getById(string $id) {
        $response = $this->client->get('venues/', [
            'query' => [
                'id' => $id
            ]
        ]);

        $responseData = json_decode($response->getBody()->getContents());
       
        $newVenue = [
            'name' => data_get($responseData, 'name'),
            'address' => data_get($responseData, 'address.localized_address_display'),
        ];

        data_set($newVenue, 'id', data_get($responseData, 'id'));

        return $newVenue;
    }

}