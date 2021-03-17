<?php

namespace LaravelLegends\InteractiveModel;

use Illuminate\Support\ServiceProvider;

class InteractiveModelServiceProvider extends ServiceProvider
{
    
    public function boot()
    {
        $this->app->runningInConsole() && $this->commands([InteractiveModelCommand::class]);
    }
}
