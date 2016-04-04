<?php

namespace EricMakesStuff\ServerMonitor\Test\Integration\Events;

use Illuminate\Support\Facades\Artisan;
use EricMakesStuff\ServerMonitor\Events\DiskUsageHealthy;
use EricMakesStuff\ServerMonitor\Test\Integration\TestCase;

class DiskUsageHealthyTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_will_fire_an_event_when_disk_usage_is_healthy()
    {
        $this->app['config']->set('server-monitor.monitors', ['DiskUsage' => [
            [
                'alarmPercentage' => 99.99,
            ],
        ]]);

        $this->expectsEvent(DiskUsageHealthy::class);

        Artisan::call('monitor:run');
    }
}
