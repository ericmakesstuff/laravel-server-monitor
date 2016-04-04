<?php

namespace EricMakesStuff\ServerMonitor\Events;

use EricMakesStuff\ServerMonitor\Monitors\SSLCertificateMonitor;

class SSLCertificateInvalid
{
    /**  @var \EricMakesStuff\ServerMonitor\Monitors\SSLCertificateMonitor|null */
    public $sslCertificateMonitor;

    /**
     * @param \EricMakesStuff\ServerMonitor\Monitors\SSLCertificateMonitor|null $sslCertificateMonitor
     */
    public function __construct(SSLCertificateMonitor $sslCertificateMonitor = null)
    {
        $this->sslCertificateMonitor = $sslCertificateMonitor;
    }
}
