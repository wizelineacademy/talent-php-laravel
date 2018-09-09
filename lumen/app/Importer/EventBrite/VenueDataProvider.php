<?php

namespace App\Importer\EventBrite;

use App\Importer\Contracts\VenueDataProvider as IVenueDataProvider;
use GuzzleHttp\Client as HttpClient;

class VenueDataProvider implements IVenueDataProvider {

    private $httpClient;

    public function __construct(HttpClient $httpClient) {
        $this->httpClient = $httpClient;
    }

    public function getById(string $venueId) {
        $httpResponse = $this->httpClient->get("venues/$venueId");
        $responseData = json_decode($httpResponse->getBody()->getContents());
        return [
            'external_id' => data_get($responseData,'id'),
            'source' => 'evenbrite',
            'name' => data_get($responseData, 'name'),
            'address' => data_get($responseData, 'address.localized_address_display')
        ];
    }
}