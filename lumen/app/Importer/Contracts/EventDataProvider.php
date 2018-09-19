<?php

namespace App\Importer\Contracts;

interface EventDataProvider {
    public function getByLocation(string $location,int $page);
    public function getByID(string $id);
}