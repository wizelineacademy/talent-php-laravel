<?php

namespace App\Jobs;

use MongoDB\Client as MongoClient;
use App\Importer\Contracts\VenueDataProvider as IVenueDataProvider;

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
    public function handle(MongoClient $client, IVenueDataProvider $venueDataProvider)
    {
        $venueStorage = $client->test->venues;

        $venue =$venueStorage->findOne([
            'external_id' => $this->venueId
        ]);

        if (empty($venue)) {
            $venue = $venueDataProvider->getById($this->venueId);
            return $venueStorage->insertOne($venue);
        }
    }
}
