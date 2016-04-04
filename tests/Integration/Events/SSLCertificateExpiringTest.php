<?php

namespace EricMakesStuff\ServerMonitor\Test\Integration\Events;

use EricMakesStuff\ServerMonitor\Events\SSLCertificateExpiring;
use EricMakesStuff\ServerMonitor\Test\Integration\TestCase;
use Illuminate\Support\Facades\Artisan;

class SSLCertificateExpiringTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_will_fire_an_event_when_certificate_is_expiring()
    {
        $this->app['config']->set('server-monitor.monitors', ['SSLCertificate' => [
            [
                'url' => 'https://www.laravel.com/',
                'alarmDaysBeforeExpiration' => range(1, (365 * 2)),
            ],
        ]]);

        $this->expectsEvent(SSLCertificateExpiring::class);

        Artisan::call('monitor:run');
    }
}
