<?php

namespace App\Importer\Contracts;

interface VenueDataProvider {
    public function get(string $id);
}
