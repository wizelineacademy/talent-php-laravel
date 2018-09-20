<?php

namespace App\Jobs;

use MongoDB\Client as MongoClient;
use App\Importer\Contracts\VenueDataProvider;

class ImportVenue extends Job
{
    protected $venueId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $venueId)
    {
        $this->venueId = $venueId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MongoClient $client, VenueDataProvider $dataProvider)
    {
        $venueStorage = $client->test->venues;

        $venue =$venueStorage->findOne([
            'external_id' => $this->venueId
        ]);

        if (empty($venue)) {
            /* TODO: Use VenueDataProvider */
            $venue = $dataProvider->getById($this->venueId);

            return $venueStorage->insertOne($venue);
        }
    }
}