<?php

namespace App\Importer\Contracts;

interface EventDataProvider {
    public function getByLocation(string $location);
    public function getByLocationPage(int $page, string $location);
    public function getByLocationPagination(string $location);
}