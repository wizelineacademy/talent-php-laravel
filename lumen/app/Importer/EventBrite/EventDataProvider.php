<?php

namespace App\Importer\EventBrite;

use App\Importer\Contracts\EventDataProvider as DataProvider;
use GuzzleHttp\Client;

class EventDataProvider implements DataProvider {

    protected $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    private function getData(array $queryArray){
        $response = $this->client->get('events/search', $queryArray);
        $responseData = json_decode($response->getBody()->getContents());

        return array_map(function ($event) {
            $newEvent = [
                'external_id' => data_get($event, 'id'),
                'name' => data_get($event, 'name.text'),
                'description' => data_get($event, 'description.text'),
                'url' => data_get($event, 'url'),
                'start' => data_get($event, 'start.utc'),
                'end' => data_get($event, 'end.utc'),
                'image_url'   => data_get($event, 'logo.original.url'),
            ];

            data_set($newEvent, 'metadata.venue_id', data_get($event, 'venue_id'));

            return $newEvent;
        }, $responseData->events);
    }

    public function getByLocation(string $location) {
        return $this->getData([
            'query' => [
                'location.address' => $location
            ]
        ]);
    }

    public function getByLocationPage(int $page, string $location)
    {
        return $this->getData([
            'query' => [
                'page' => $page,
                'location.address' => $location
            ]
        ]);
    }

    public function getByLocationPagination(string $location)
    {
        $response     = $this->client->get('events/search', [
            'query' => [
                'location.address' => $location
            ]
        ]);
         $responseData = json_decode($response->getBody()->getContents());
        return $responseData->pagination;
    }
}