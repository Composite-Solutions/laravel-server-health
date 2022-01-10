<?php

namespace Composite\ServerHealth;

use Illuminate\Support\ServiceProvider;


class HealthServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}
