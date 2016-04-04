<?php

namespace EricMakesStuff\ServerMonitor\Test\Integration\Events;

use EricMakesStuff\ServerMonitor\Events\HttpPingUp;
use Illuminate\Support\Facades\Artisan;
use EricMakesStuff\ServerMonitor\Test\Integration\TestCase;

class HttpPingUpTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_will_fire_an_event_when_http_is_up()
    {
        $this->app['config']->set('server-monitor.monitors', ['HttpPing' => [
            [
                'url' => 'http://www.example.com/',
            ],
        ]]);

        $this->expectsEvent(HttpPingUp::class);

        Artisan::call('monitor:run');
    }

    /** @test */
    public function it_will_fire_an_event_when_http_is_up_and_phrase_is_found()
    {
        $this->app['config']->set('server-monitor.monitors', ['HttpPing' => [
            [
                'url' => 'http://www.example.com/',
                'checkPhrase' => 'Example Domain',
            ],
        ]]);

        $this->expectsEvent(HttpPingUp::class);

        Artisan::call('monitor:run');
    }
}
