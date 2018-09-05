<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportEvents extends Command {

    protected $signature = 'import:events {location}';

    protected $description = 'Import events from EventBrite';

    public function handle() {
        $location = $this->argument('location');
        $this->line("Executed!! $location");
    }
}