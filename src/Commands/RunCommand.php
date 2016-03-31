<?php

namespace EricMakesStuff\ServerMonitor\Commands;

use EricMakesStuff\ServerMonitor\Monitors\BaseMonitor;
use EricMakesStuff\ServerMonitor\Monitors\ServerMonitorFactory;

class RunCommand extends BaseCommand
{
    /**
     * @var string
     */
    protected $signature = 'monitor:run {monitor? : Comma-delimited list of names of specific monitors to run}';

    /**
     * @var string
     */
    protected $description = 'Run all server monitor tasks.';

    public function handle()
    {
        $monitors = ServerMonitorFactory::createForMonitorConfig(
            config('server-monitor.monitors'),
            explode(',', $this->argument('monitor'))
        );

        $monitors->each(function (BaseMonitor $monitor) {
            $monitor->runMonitor();
        });
    }
}
