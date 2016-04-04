<?php

namespace EricMakesStuff\ServerMonitor\Test\Integration\Events;

use EricMakesStuff\ServerMonitor\Events\SSLCertificateInvalid;
use EricMakesStuff\ServerMonitor\Test\Integration\TestCase;
use Illuminate\Support\Facades\Artisan;

class SSLCertificateInvalidTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_will_fire_an_event_when_certificate_is_invalid()
    {
        $this->app['config']->set('server-monitor.monitors', ['SSLCertificate' => [
            [
                'url' => 'https://www.implode.com/',
            ],
        ]]);

        $this->expectsEvent(SSLCertificateInvalid::class);

        Artisan::call('monitor:run');
    }
}
