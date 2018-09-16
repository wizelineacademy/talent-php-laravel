<?php

namespace App\Importer\Contracts;

interface VenueDataProvider {
    public function getByID(string $location);
}