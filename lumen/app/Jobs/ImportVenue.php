<?php

namespace App\Jobs;

use MongoDB\Client as MongoClient;

class ImportVenue extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($venueId)
    {
        $this->venueId = $venueId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MongoClient $client)
    {
        $venueStorage = $client->test->venues;

        $venue =$venueStorage->findOne([
            'external_id' => $this->venueId
        ]);

        if (empty($venue)) {
            /* TODO: Use VenueDataProvider */
            $venue = [
                'external_id' => $this->venueId,
                'name' => 'Wizeline'
            ];

            return $venueStorage->insertOne($venue);
        }
    }
}
