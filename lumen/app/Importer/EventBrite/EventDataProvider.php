<?php

namespace App\Importer\EventBrite;

use App\Importer\Contracts\EventDataProvider as DataProvider;
use GuzzleHttp\Client;

class EventDataProvider implements DataProvider {

    protected $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function getByLocation(string $location, int $page) {
        $response = $this->client->get('events/search', [
            'query' => [
                'location.address' => $location,
                'page'=> $page,
            ]
        ]);

        $responseData = json_decode($response->getBody()->getContents());

        $theEvents= array_map(function ($event) {
            $newEvent = [
                'external_id' => data_get($event, 'id'),
                'name' => data_get($event, 'name.text'),
                'description' => data_get($event, 'description.text'),
                'url' => data_get($event, 'url'),
                'start' => data_get($event, 'start.utc'),
                'end' => data_get($event, 'end.utc'),
            ];

            data_set($newEvent, 'metadata.venue_id', data_get($event, 'venue_id'));

            return $newEvent;
        }, $responseData->events);

        $thePagination=$responseData->pagination;

        $toReturn = [
            'pagination'=>$thePagination,
            'events'=>$theEvents,
        ];

        return $toReturn;
    }

    public function getByID(string $id) {
        $response = $this->client->get('venues/'.$id);

        $responseData = json_decode($response->getBody()->getContents());
       
        $newVenue = [
            'name' => data_get($responseData, 'name'),
            'address' => data_get($responseData, 'address.localized_address_display'),
        ];

        data_set($newVenue, 'external_id', data_get($responseData, 'id'));

        return $newVenue;
    }
}