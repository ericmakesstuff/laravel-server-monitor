<?php

namespace EricMakesStuff\ServerMonitor\Events;

use EricMakesStuff\ServerMonitor\Monitors\DiskUsageMonitor;

class DiskUsageHealthy
{
    /**  @var \EricMakesStuff\ServerMonitor\Monitors\DiskUsageMonitor|null */
    public $diskUsageMonitor;

    /**
     * @param \EricMakesStuff\ServerMonitor\Monitors\DiskUsageMonitor $diskUsageMonitor
     */
    public function __construct(DiskUsageMonitor $diskUsageMonitor)
    {
        $this->diskUsageMonitor = $diskUsageMonitor;
    }
}
