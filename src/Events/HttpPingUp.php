<?php

namespace EricMakesStuff\ServerMonitor\Events;

use EricMakesStuff\ServerMonitor\Monitors\HttpPingMonitor;

class HttpPingUp
{
    /**  @var \EricMakesStuff\ServerMonitor\Monitors\HttpPingMonitor|null */
    public $httpPingMonitor;

    /**
     * @param \EricMakesStuff\ServerMonitor\Monitors\HttpPingMonitor $httpPingMonitor
     */
    public function __construct(HttpPingMonitor $httpPingMonitor)
    {
        $this->httpPingMonitor = $httpPingMonitor;
    }
}
