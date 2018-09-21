<?php

namespace App\Jobs;

use MongoDB\Client as MongoClient;
use App\Importer\Contracts\VenueDataProvider as VenueDataProvider;
use App\Jobs\ImportVenue;

class ImportVenue extends Job
{   
    protected $venue;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($venue)
    {
        $this->venue = $venue;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MongoClient $client)
    {
        $venueStorage = $client->test->venues;
        $toImport = $this->venue;

        $venue = $venueStorage->findOne([
            'external_id' => data_get($toImport, 'external_id')
        ]);

        if (empty($venue)) {
            $venue = $this->venue;
            return $venueStorage->insertOne($venue);
        }
    }
}
