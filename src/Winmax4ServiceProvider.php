<?php

namespace Controlink\Winmax4;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class Winmax4ServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'winmax4');

        $this->publishes([
            __DIR__.'/config/winmax4.php' => config_path('winmax4.php'),
            __DIR__.'/lang' => resource_path('lang/controlink/winmax4')
        ]);


    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }
}
