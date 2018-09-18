<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\App\Providers\MongoServiceProvider::class);

        $this->app->register(\Illuminate\Redis\RedisServiceProvider::class);

        $this->app->singleton(\App\Importer\Contracts\EventDataProvider::class, function () {
            $token = config('services.eventbrite.token');
            $client = new \GuzzleHttp\Client([
                'base_uri' => config('services.eventbrite.base_uri'),
                'headers' => [
                    'Authorization' => "Bearer $token"
                ]
            ]);
            return new \App\Importer\EventBrite\EventDataProvider($client);
        });

        $this->app->singleton(\App\Importer\Contracts\VenueDataProvider::class, function () {
            $token = config('services.eventbrite.token');
            $client = new \GuzzleHttp\Client([
                'base_uri' => config('services.eventbrite.base_uri'),
                'headers' => [
                    'Authorization' => "Bearer $token"
                ]
            ]);
            return new \App\Importer\EventBrite\VenueDataProvider($client);
        });
    }
}
