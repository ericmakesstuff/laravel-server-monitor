<?php

namespace EricMakesStuff\ServerMonitor\Test\Integration\Events;

use EricMakesStuff\ServerMonitor\Events\HttpPingDown;
use Illuminate\Support\Facades\Artisan;
use EricMakesStuff\ServerMonitor\Test\Integration\TestCase;

class HttpPingDownTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_will_fire_an_event_when_http_is_down()
    {
        $this->app['config']->set('server-monitor.monitors', ['HttpPing' => [
            [
                'url' => 'http://somelongdomainthatdoesntexist12345asdf.com',
                'timeout' => 1,
                'allowRedirects' => false,
            ],
        ]]);

        $this->expectsEvent(HttpPingDown::class);

        Artisan::call('monitor:run');
    }

    /** @test */
    public function it_will_fire_an_event_when_page_not_found()
    {
        $this->app['config']->set('server-monitor.monitors', ['HttpPing' => [
            [
                'url' => 'http://www.example.com/bad/path',
                'allowRedirects' => false,
            ],
        ]]);

        $this->expectsEvent(HttpPingDown::class);

        Artisan::call('monitor:run');
    }

    /** @test */
    public function it_will_fire_an_event_when_http_is_up_and_phrase_not_found()
    {
        $this->app['config']->set('server-monitor.monitors', ['HttpPing' => [
            [
                'url' => 'http://www.example.com/',
                'checkPhrase' => 'This will not be found! ASDF!',
            ],
        ]]);

        $this->expectsEvent(HttpPingDown::class);

        Artisan::call('monitor:run');
    }
}
