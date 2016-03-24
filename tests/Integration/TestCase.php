<?php

namespace EricMakesStuff\ServerMonitor\Test\Integration;

use EricMakesStuff\ServerMonitor\ServerMonitorServiceProvider;
use Event;
use Exception;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ServerMonitorServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'prefix' => '',
        ]);

        $app['config']->set('filesystems.disks.local', [
            'driver' => 'local',
        ]);

        $app['config']->set('filesystems.disks.secondLocal', [
            'driver' => 'local',
        ]);

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
    }

    protected function expectsEvent($eventClassName)
    {
        Event::listen($eventClassName, function ($event) use ($eventClassName) {
            $this->firedEvents[] = $eventClassName;
        });

        $this->beforeApplicationDestroyed(function () use ($eventClassName) {
            $firedEvents = isset($this->firedEvents) ? $this->firedEvents : [];

            if (!in_array($eventClassName, $firedEvents)) {
                throw new Exception("Event {$eventClassName} not fired");
            }
        });
    }

    protected function seeInConsoleOutput($expectedText)
    {
        $consoleOutput = $this->app[Kernel::class]->output();

        $this->assertContains($expectedText, $consoleOutput, "Did not see `{$expectedText}` in console output: `$consoleOutput`");
    }

    protected function doNotSeeInConsoleOutput($unExpectedText)
    {
        $consoleOutput = $this->app[Kernel::class]->output();

        $this->assertNotContains($unExpectedText, $consoleOutput, "Did not expect to see `{$unExpectedText}` in console output: `$consoleOutput`");
    }
}
