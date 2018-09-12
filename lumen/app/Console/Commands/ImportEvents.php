<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Importer\Contracts\EventDataProvider;
use MongoDB\Client as MongoClient;

class ImportEvents extends Command {

    protected $signature = 'import:events {location}';

    protected $description = 'Import events from EventBrite';

    protected $eventDataProvider;

    public function __construct(EventDataProvider $eventDataProvider) {
        parent::__construct();
        $this->eventDataProvider = $eventDataProvider;
    }

    public function handle() {
        $location = $this->argument('location');
        $page = 1;
        do {
            $paginatedEvents = $this->eventDataProvider->getPaginatedByLocation($location, $page);
            $this->info("Fetched page $page of {$paginatedEvents->pagination->page_count}");
            foreach ($paginatedEvents->events as $event) {
                dispatch(new \App\Jobs\ImportEvent($event));
            }
            $page++;
        } while($paginatedEvents->pagination->has_more_items);
    }
}