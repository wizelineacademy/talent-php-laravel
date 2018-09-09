<?php

namespace App\Importer\Contracts;

interface EventDataProvider {
    public function getByLocation(string $location);
    public function getPageByLocation(int $page, string $location);
    public function getPaginationMetadataByLocation(string $location);
}