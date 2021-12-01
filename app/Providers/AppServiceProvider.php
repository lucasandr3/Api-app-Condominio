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
        $this->app->bind(\App\Interfaces\Services\DocServiceInterface::class, \App\Services\DocService::class);
        $this->app->bind(\App\Interfaces\Repositories\DocRepositoryInterface::class, \App\Repositories\DocRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
