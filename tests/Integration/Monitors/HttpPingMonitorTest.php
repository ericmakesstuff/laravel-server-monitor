<?php

namespace EricMakesStuff\ServerMonitor\Test\Integration\Monitors;

use EricMakesStuff\ServerMonitor\Events\DiskUsageHealthy;
use EricMakesStuff\ServerMonitor\Exceptions\InvalidConfiguration;
use EricMakesStuff\ServerMonitor\Exceptions\InvalidPath;
use EricMakesStuff\ServerMonitor\Monitors\DiskUsageMonitor;
use EricMakesStuff\ServerMonitor\Monitors\HttpPingMonitor;
use EricMakesStuff\ServerMonitor\Test\Integration\TestCase;

class HttpPingMonitorTest extends TestCase
{
    /** @test */
    public function it_can_monitor_a_demo_url_successfully()
    {
        $httpPingMonitor = new HttpPingMonitor([
            'url' => 'http://www.example.com/',
        ]);

        $httpPingMonitor->runMonitor();

        $this->assertEquals('200', $httpPingMonitor->getResponseCode());
    }

    /** @test */
    public function it_can_find_text_in_a_response()
    {
        $httpPingMonitor = new HttpPingMonitor([
            'url' => 'http://www.example.com/',
            'checkPhrase' => 'Example Domain',
        ]);

        $httpPingMonitor->runMonitor();

        $this->assertTrue($httpPingMonitor->getResponseContainsPhrase());
    }

    /** @test */
    public function it_can_recognize_a_page_not_found_response()
    {
        $httpPingMonitor = new HttpPingMonitor([
            'url' => 'http://www.example.com/bad/path',
            'allowRedirects' => false,
        ]);

        $httpPingMonitor->runMonitor();

        $this->assertEquals('404', $httpPingMonitor->getResponseCode());
    }

    /** @test */
    public function it_can_fail_gracefully()
    {
        $httpPingMonitor = new HttpPingMonitor([
            'url' => 'http://somelongdomainthatdoesntexist12345asdf.com',
            'timeout' => 1,
            'allowRedirects' => false,
        ]);

        $httpPingMonitor->runMonitor();

        $this->assertEmpty($httpPingMonitor->getResponseCode());
    }

    /** @test */
    public function it_throws_an_exception_for_nonexistent_url()
    {
        $httpPingMonitor = new HttpPingMonitor([]);

        $this->setExpectedException(InvalidConfiguration::class);

        $httpPingMonitor->runMonitor();
    }
}
