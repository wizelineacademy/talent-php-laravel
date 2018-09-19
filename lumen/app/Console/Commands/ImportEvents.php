<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Importer\Contracts\EventDataProvider;
use MongoDB\Client as MongoClient;

class ImportEvents extends Command {

    protected $signature = 'import:events {location}';

    protected $description = 'Import events from EventBrite';

    protected $eventDataProvider;

    public function __construct(EventDataProvider $eventDataProvider ) {
        parent::__construct();
        $this->eventDataProvider = $eventDataProvider;
       // $this->venueDataProvider = $venueDataProvider;
    }

    public function handle() {
        $location = $this->argument('location');
        $page=1;
       do {
        $bdData = $this->eventDataProvider->getByLocation($location,$page);
        $this->thePage =data_get($bdData,"pagination.page_number");
            foreach ($bdData['events'] as $event) {
                dispatch(new \App\Jobs\ImportEvent($event));
                $vid = data_get($event,"metadata.venue_id");
                $venue = $this->eventDataProvider->getByID($vid);
                dispatch(new \App\Jobs\ImportVenue($venue));  
            }
            $page++;
        }while (data_get($bdData,"pagination.has_more_items"));
    }
}