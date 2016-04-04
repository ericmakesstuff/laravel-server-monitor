<?php

namespace EricMakesStuff\ServerMonitor\Test\Integration\Events;

use Illuminate\Support\Facades\Artisan;
use EricMakesStuff\ServerMonitor\Events\DiskUsageAlarm;
use EricMakesStuff\ServerMonitor\Test\Integration\TestCase;

class DiskUsageAlarmTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_will_fire_an_event_disk_usage_is_too_high()
    {
        $this->app['config']->set('server-monitor.monitors', ['DiskUsage' => [
            [
                'alarmPercentage' => 0.01,
            ],
        ]]);

        $this->expectsEvent(DiskUsageAlarm::class);

        Artisan::call('monitor:run');
    }
}
