<?php

namespace App\Jobs;

use MongoDB\Client as MongoClient;
use App\Jobs\ImportVenue;
use App\Jobs\ImportEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportEvent implements ShouldQueue
{

    use Queueable;

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
        $venueId = data_get($this->event, 'metadata.venue_id');

        $venueStorage = $client->test->venues;
        $eventStorage = $client->test->events;

        $venue = $venueStorage->findOne([
            'external_id' => $venueId
        ]);

        if ($venue) {
            $event = $this->event;
            $event['venue'] = $venue;
            return $eventStorage->insertOne($event);
        }

        dispatch(new ImportVenue($venueId));
        dispatch(new ImportEvent($this->event));
    }
}
