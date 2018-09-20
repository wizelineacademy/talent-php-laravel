<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Importer\Contracts\EventDataProvider;
use Illuminate\Support\Facades\Queue;
use \App\Jobs\ImportEvent;

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
        $numberOfItems = 0;
        
        $pagination = $this->eventDataProvider->getByLocationPagination($location);

        $page = 1;
        do {
            $events = $this->eventDataProvider->getByLocationPage($page, $location);
            foreach ($events as $event) {
                Queue::push(new ImportEvent($event));
            }
            $numberOfItems = count($events);
            $page++;
        } while($pagination->page_count > $page);

        $this->line("Imported Events: $numberOfItems");
    }
}