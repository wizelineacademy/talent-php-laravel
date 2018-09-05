<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MongoDB\Client as MongoClient;

class MongoServiceProvider extends ServiceProvider {
    
    public function register() {
        $this->app->singleton(MongoClient::class, function() {
            $host = config('services.mongodb.host');
            return new MongoClient("mongodb://$host");
        });
    }
}