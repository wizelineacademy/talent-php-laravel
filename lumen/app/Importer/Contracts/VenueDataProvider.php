<?php

namespace App\Importer\Contracts;

interface VenueDataProvider {
    public function getById(string $venueId);
}