<?php

namespace EricMakesStuff\ServerMonitor\Test\Integration\Events;

use EricMakesStuff\ServerMonitor\Events\SSLCertificateValid;
use EricMakesStuff\ServerMonitor\Test\Integration\TestCase;
use Illuminate\Support\Facades\Artisan;

class SSLCertificateValidTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_will_fire_an_event_when_certificate_is_valid()
    {
        $this->app['config']->set('server-monitor.monitors', ['SSLCertificate' => [
            [
                'url' => 'https://www.google.com/',
            ],
        ]]);

        $this->expectsEvent(SSLCertificateValid::class);

        Artisan::call('monitor:run');
    }
}
