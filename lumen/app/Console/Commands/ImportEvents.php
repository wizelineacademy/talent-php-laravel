<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Importer\Contracts\EventDataProvider;
use App\Importer\Contracts\VenueDataProvider;

class ImportEvents extends Command {

    protected $signature = 'import:events {location}';

    protected $description = 'Import events from EventBrite';

    protected $eventDataProvider;

    protected $venueDataProvider;

    public function __construct(EventDataProvider $eventDataProvider, VenueDataProvider $venueDataProvider) {
        parent::__construct();
        $this->eventDataProvider = $eventDataProvider;
        $this->venueDataProvider = $venueDataProvider;
    }

    public function handle() {
        $location = $this->argument('location');
        $page = 1;

        do {
            $events = $this->eventDataProvider->getByLocation($location, $page);
            $pageNumber = data_get($events, 'pagination.page_number');

            foreach ($events['events'] as $event) {
                dispatch(new \App\Jobs\ImportEvent($event));
                $venue_id = data_get($event, 'metadata.venue_id');
                $venue = $this->venueDataProvider->getById($venue_id);
                dispatch(new \App\Jobs\ImportVenue($venue));
            }

            $page++;

        } while (data_get($events, 'pagination.has_more_items'));
    }
}