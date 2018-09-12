<?php

namespace App\Importer\Contracts;

interface EventDataProvider {
    public function getByLocation(string $location);
    public function getPaginatedByLocation(string $location, int $page): object;
}