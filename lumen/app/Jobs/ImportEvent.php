<?php

namespace App\Jobs;

use MongoDB\Client as MongoClient;
use App\Jobs\ImportEvent;

class ImportEvent extends Job
{   
    protected $event;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MongoClient $client)
    {
        $eventStorage = $client->test->events;

        $toImport = $this->event;

        $event = $eventStorage->findOne([
            'external_id' => data_get($toImport, 'external_id')
        ]); 

        if (empty($event)) {
            $venueStorage = $client->test->venues;
            $venueId = data_get($toImport, 'metadata.venue_id');
            
            return $eventStorage->insertOne($toImport);
        }
    }
}
