<?php

namespace EricMakesStuff\ServerMonitor\Notifications;

use EricMakesStuff\ServerMonitor\Events\HttpPingDown;
use EricMakesStuff\ServerMonitor\Events\HttpPingUp;
use EricMakesStuff\ServerMonitor\Events\DiskUsageAlarm;
use EricMakesStuff\ServerMonitor\Events\DiskUsageHealthy;
use EricMakesStuff\ServerMonitor\Events\SSLCertificateExpiring;
use EricMakesStuff\ServerMonitor\Events\SSLCertificateInvalid;
use EricMakesStuff\ServerMonitor\Events\SSLCertificateValid;
use Illuminate\Events\Dispatcher;

class EventHandler
{
    /**
     * @var \EricMakesStuff\ServerMonitor\Notifications\Notifier
     */
    protected $notifier;

    public function __construct()
    {
        $notifierClass = config('server-monitor.notifications.handler');

        $this->notifier = app($notifierClass);
    }

    /**
     * @param \EricMakesStuff\ServerMonitor\Events\DiskUsageAlarm $event
     */
    public function whenDiskUsageAlarm(DiskUsageAlarm $event)
    {
        $this->notifier->diskUsageAlarm($event->diskUsageMonitor);
    }

    /**
     * @param \EricMakesStuff\ServerMonitor\Events\DiskUsageHealthy $event
     */
    public function whenDiskUsageHealthy(DiskUsageHealthy $event)
    {
        $this->notifier->diskUsageHealthy($event->diskUsageMonitor);
    }

    /**
     * @param \EricMakesStuff\ServerMonitor\Events\HttpPingDown $event
     */
    public function whenHttpPingDown(HttpPingDown $event)
    {
        $this->notifier->httpPingDown($event->httpPingMonitor);
    }

    /**
     * @param \EricMakesStuff\ServerMonitor\Events\HttpPingUp $event
     */
    public function whenHttpPingUp(HttpPingUp $event)
    {
        $this->notifier->httpPingUp($event->httpPingMonitor);
    }

    /**
     * @param \EricMakesStuff\ServerMonitor\Events\SSLCertificateValid $event
     */
    public function whenSSLCertificateValid(SSLCertificateValid $event)
    {
        $this->notifier->sslCertificateValid($event->sslCertificateMonitor);
    }

    /**
     * @param \EricMakesStuff\ServerMonitor\Events\SSLCertificateInvalid $event
     */
    public function whenSSLCertificateInvalid(SSLCertificateInvalid $event)
    {
        $this->notifier->sslCertificateInvalid($event->sslCertificateMonitor);
    }

    /**
     * @param \EricMakesStuff\ServerMonitor\Events\SSLCertificateExpiring $event
     */
    public function whenSSLCertificateExpiring(SSLCertificateExpiring $event)
    {
        $this->notifier->sslCertificateExpiring($event->sslCertificateMonitor);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     *
     * @return array
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            DiskUsageHealthy::class,
            static::class.'@whenDiskUsageHealthy'
        );

        $events->listen(
            DiskUsageAlarm::class,
            static::class.'@whenDiskUsageAlarm'
        );

        $events->listen(
            HttpPingUp::class,
            static::class.'@whenHttpPingUp'
        );

        $events->listen(
            HttpPingDown::class,
            static::class.'@whenHttpPingDown'
        );

        $events->listen(
            SSLCertificateValid::class,
            static::class.'@whenSSLCertificateValid'
        );

        $events->listen(
            SSLCertificateInvalid::class,
            static::class.'@whenSSLCertificateInvalid'
        );

        $events->listen(
            SSLCertificateExpiring::class,
            static::class.'@whenSSLCertificateExpiring'
        );
    }
}
