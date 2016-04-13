<?php

namespace HighIdeas\UsersOnline;

use Illuminate\Support\ServiceProvider;

class UsersOnlineServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'usersonline');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        $this->app->make('HighIdeas\UsersOnline\Controllers\UsersOnlineController');
    }
}

