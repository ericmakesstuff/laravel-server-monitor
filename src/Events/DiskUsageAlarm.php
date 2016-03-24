<?php

namespace EricMakesStuff\ServerMonitor\Events;

use EricMakesStuff\ServerMonitor\Monitors\DiskUsageMonitor;

class DiskUsageAlarm
{
    /**  @var \EricMakesStuff\ServerMonitor\Monitors\DiskUsageMonitor|null */
    public $diskUsageMonitor;

    /**
     * @param \EricMakesStuff\ServerMonitor\Monitors\DiskUsageMonitor|null $diskUsageMonitor
     */
    public function __construct(DiskUsageMonitor $diskUsageMonitor = null)
    {
        $this->diskUsageMonitor = $diskUsageMonitor;
    }
}
