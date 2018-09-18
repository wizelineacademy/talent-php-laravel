<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Importer\Contracts\EventDataProvider;
use App\Jobs\ImportEvent;
use App\Jobs\ImportLocation;

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
        
<<<<<<< HEAD
        $events = $this->eventDataProvider->getByLocation($location);

        foreach ($events as $event) {
            dispatch(new \App\Jobs\ImportEvent($event));
        }
=======
        dispatch(new ImportLocation($location));
>>>>>>> Finished project
    }
}