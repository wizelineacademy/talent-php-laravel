<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Importer\Contracts\EventDataProvider;
use MongoDB\Client as MongoClient;

class ImportEvents extends Command {

    protected $signature = 'import:events {location}';

    protected $description = 'Import events from EventBrite';

    protected $eventDataProvider;

    public function __construct(EventDataProvider $eventDataProvider, MongoClient $client) {
        parent::__construct();
        $this->eventDataProvider = $eventDataProvider;
        $this->client = $client;
    }

    public function handle() {
        $location = $this->argument('location');
        
        $events = $this->eventDataProvider->getByLocation($location);

        $eventStorage = $this->client->test->events;

        $result = $eventStorage->insertMany($events);

        $insertedCount = $result->getInsertedCount();
        $this->line("Imported Events: $insertedCount");
    }
}