<?php

namespace EricMakesStuff\ServerMonitor\Events;

use EricMakesStuff\ServerMonitor\Monitors\SSLCertificateMonitor;

class SSLCertificateValid
{
    /**  @var \EricMakesStuff\ServerMonitor\Monitors\SSLCertificateMonitor|null */
    public $sslCertificateMonitor;

    /**
     * @param \EricMakesStuff\ServerMonitor\Monitors\SSLCertificateMonitor $sslCertificateMonitor
     */
    public function __construct(SSLCertificateMonitor $sslCertificateMonitor)
    {
        $this->sslCertificateMonitor = $sslCertificateMonitor;
    }
}
