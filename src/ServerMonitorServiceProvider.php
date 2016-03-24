<?php

namespace EricMakesStuff\ServerMonitor;

use EricMakesStuff\ServerMonitor\Commands\RunCommand;
use Illuminate\Support\ServiceProvider;
use EricMakesStuff\ServerMonitor\Helpers\ConsoleOutput;

class ServerMonitorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/server-monitor.php' => config_path('server-monitor.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/server-monitor.php', 'server-monitor');

        $this->app['events']->subscribe(\EricMakesStuff\ServerMonitor\Notifications\EventHandler::class);

        $this->app->bind('command.monitor:run', RunCommand::class);

        $this->commands([
            'command.monitor:run',
        ]);

        $this->app->singleton(ConsoleOutput::class);
    }
}
