<?php

namespace App\Jobs;

use App\Importer\Contracts\EventDataProvider;


class ImportLocation extends Job
{

    protected $location;
    protected $page;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($location, $page = 1)
    {
        $this->location = $location;
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EventDataProvider $eventDataProvider)
    {
        $result = $eventDataProvider->getByLocation($this->location, $this->page);

        foreach($result->items as $event) {
            dispatch(new ImportEvent($event));
        }

        if($result->hasMore) {
            dispatch(new ImportLocation($this->location, $this->page + 1));
        }
    }
}
