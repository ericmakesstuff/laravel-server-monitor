<?php

namespace EricMakesStuff\ServerMonitor\Test\Integration;

use EricMakesStuff\ServerMonitor\Exceptions\InvalidConfiguration;
use Illuminate\Support\Facades\Artisan;

class RunCommandTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('server-monitor.monitors.DiskUsage', [
            [
                'alarmPercentage' => 99.99,
            ],
        ]);
    }

    /** @test */
    public function it_can_monitor_disk_usage()
    {
        $resultCode = Artisan::call('monitor:run');

        $this->assertEquals(0, $resultCode);
    }

    /** @test */
    public function it_throws_an_exception_for_invalid_configuration()
    {
        $this->app['config']->set('server-monitor.monitors.InvalidMonitor', [
            [
                'alarmPercentage' => 75,
            ],
        ]);

        $this->setExpectedException(InvalidConfiguration::class);

        $resultCode = Artisan::call('monitor:run');

        $this->assertNotEquals(0, $resultCode);
    }
}
