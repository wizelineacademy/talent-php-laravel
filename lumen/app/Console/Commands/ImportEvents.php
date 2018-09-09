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
        $importedItemsCount = 0;
        $location = $this->argument('location');

        $this->info("Starting Import of event in $location");
        $this->info('Getting Info to start to import');
        $paginationMetadata = $this->eventDataProvider->getPaginationMetadataByLocation($location);

        for ($page = 1; $page <= $paginationMetadata->page_count; $page++) {
            $this->info("Getting Page $page");
            $events = $this->eventDataProvider->getPageByLocation($page, $location);
            foreach ($events as $event) {
                dispatch(new \App\Jobs\ImportEvent($event));
            }
            $importedItemsCount += count($events);
        }

        $this->info("Event Import Completed :: Total Importted Item $importedItemsCount");
    }
}