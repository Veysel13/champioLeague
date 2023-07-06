<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\Team\TeamInterface::class,
            \App\Repositories\Team\TeamRepository::class
        );

        $this->app->bind(
            \App\Repositories\Match\MatchInterface::class,
            \App\Repositories\Match\MatchRepository::class
        );

        $this->app->bind(
            \App\Repositories\Match\MatchStoreInterface::class,
            \App\Repositories\Match\MatchStoreRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
