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
    public function __construct($venueId)
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
        $venuesStorage = $client->test->venues;

        $venue = $venuesStorage->findOne([
            'external_id' => $this->venueId
        ]);

        if(empty($venue)) {
            $venue = $dataProvider->get($this->venueId);

            return $venuesStorage->insertOne($venue);
        }
    }
}
