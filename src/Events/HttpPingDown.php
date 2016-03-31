<?php

namespace EricMakesStuff\ServerMonitor\Events;

use EricMakesStuff\ServerMonitor\Monitors\HttpPingMonitor;

class HttpPingDown
{
    /**  @var \EricMakesStuff\ServerMonitor\Monitors\HttpPingMonitor|null */
    public $httpPingMonitor;

    /**
     * @param \EricMakesStuff\ServerMonitor\Monitors\HttpPingMonitor|null $httpPingMonitor
     */
    public function __construct(HttpPingMonitor $httpPingMonitor = null)
    {
        $this->httpPingMonitor = $httpPingMonitor;
    }
}
