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
    public function __construct($venueId)
    {
       
        $this->venueId = $venueId;
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

        $toImport = $this->venue;

        $venue =$venueStorage->findOne([
            'external_id' => data_get($toImport, 'external_id')
        ]);

        if (empty($venue)) {
            /* TODO: Use VenueDataProvider */
            // $venue = $this->venueDataProvider->getByID($this->venueId);
            // $venue = [
            //     'external_id' => $this->venueId,
            //     'name' => 'Wizeline'
            // ];
           
        }
        $venue = $this->venueId;
        return $venueStorage->insertOne($venue);
    }
}
