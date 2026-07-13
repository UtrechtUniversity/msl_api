<?php

namespace App\Providers;

use App\Listeners\LogMailSendListener;
use App\Scout\CkanSearchEngine;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        Blade::anonymousComponentPath(resource_path('views/public/components/'));
        Event::listen(
            MessageSent::class,
            LogMailSendListener::class
        );
        resolve(EngineManager::class)->extend('ckan', function () {
            return new CkanSearchEngine();
        });
    }
}
