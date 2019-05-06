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
        $this->publishes([
            __DIR__.'/Config/usersonline.php' => config_path('usersonline.php'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/Config/usersonline.php', 'usersonline');
    }
}