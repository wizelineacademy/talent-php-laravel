<?php

namespace App\Importer\Contracts;

interface EventDataProvider {
    public function getByLocation(string $location, $page);
}