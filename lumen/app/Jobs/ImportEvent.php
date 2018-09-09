<?php

namespace App\Jobs;

use MongoDB\Client as MongoClient;
use Illuminate\Support\Facades\Queue;

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
            
            $venue = $venueStorage->findOne([
                'external_id' => $venueId
            ]);

            if (!empty($venue)) {
                data_set($toImport, 'venue', $venue);
                return $eventStorage->insertOne($toImport);
            }

            Queue::push(new ImportVenue($venueId));
            Queue::push(new ImportEvent($toImport));
        }
    }
}
