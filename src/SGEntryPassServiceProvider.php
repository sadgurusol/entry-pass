<?php

namespace Sadguru\SGEntryPass;
use Illuminate\Support\Facades\Route;
use \Illuminate\Support\ServiceProvider;
class SGEntryPassServiceProvider extends ServiceProvider
{

    public function boot(){

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'SGEntryPass');

        Route::group(['middleware'=>'web'], function (){
            $this->loadRoutesFrom(__DIR__ . '/routes.php');
        });

        $this->loadViewsFrom(__DIR__.'/resources/views/', 'SGEntryPass');


        $this->publishes([
            __DIR__.'/config/config.php' => config_path('sgentrypass.php'),
        ], 'config');
    }
}
