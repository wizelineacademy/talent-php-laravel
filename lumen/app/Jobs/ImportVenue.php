<?php

namespace App\Jobs;

use MongoDB\Client as MongoClient;

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
    public function handle(MongoClient $client)
    {
        $venue = [
            'external_id' => $this->venueId,
            'name' => 'Wizeline',
        ];


        $venueStorage = $client->test->venues;

        $venueStorage->insertOne($venue);
    }
}
