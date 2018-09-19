<?php

namespace App\Jobs;

use MongoDB\Client as MongoClient;
use App\Jobs\ImportVenue;
use App\Importer\Contracts\EventDataProvider;

class ImportVenue extends Job
{   
    protected $venueId;
    protected $venue;
    protected $venueDataProvider;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($venue)
    {
        $this->venue = $venue;
        
        //$this->venueDataProvider = $venueDataProvider;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MongoClient $client)
    {
        $venueStorage = $client->test->venues;
        $theVenue = $venueStorage->findOne([
            'external_id'=> data_get($this->venue,'external_id'),
        ]);
        // $toImport = $this->venue;
        if (empty($theVenue)) {
            $venue = $this->venue;
            return $venueStorage->insertOne($venue);
        }
        
    }
}
