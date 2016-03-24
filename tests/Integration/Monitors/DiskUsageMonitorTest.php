<?php

namespace EricMakesStuff\ServerMonitor\Test\Integration\Monitors;

use EricMakesStuff\ServerMonitor\Events\DiskUsageHealthy;
use EricMakesStuff\ServerMonitor\Exceptions\InvalidPath;
use EricMakesStuff\ServerMonitor\Monitors\DiskUsageMonitor;
use EricMakesStuff\ServerMonitor\Test\Integration\TestCase;

class DiskUsageMonitorTest extends TestCase
{
    /** @test */
    public function it_can_calculate_percentage_remaining_on_an_existing_disk()
    {
        $diskUsageMonitor = new DiskUsageMonitor([
            'path' => __DIR__,
        ]);

        $diskUsageMonitor->runMonitor();

        $this->assertGreaterThan(0, $diskUsageMonitor->getPercentageUsed());
    }

    /** @test */
    public function it_can_populate_disk_values()
    {
        $diskUsageMonitor = new DiskUsageMonitor([
            'path' => __DIR__,
        ]);

        $diskUsageMonitor->runMonitor();

        $this->assertGreaterThan(0, $diskUsageMonitor->getFreeSpace());
        $this->assertGreaterThan(0, $diskUsageMonitor->getTotalSpace());
        $this->assertGreaterThan(0, $diskUsageMonitor->getUsedSpace());
    }

    /** @test */
    public function it_throws_an_exception_for_nonexistent_disk()
    {
        $diskUsageMonitor = new DiskUsageMonitor([
            'path' => '/a234k3l2k3j4l23k/23l4j2l34j',
        ]);

        $this->setExpectedException(InvalidPath::class);

        $diskUsageMonitor->runMonitor();
    }
}
