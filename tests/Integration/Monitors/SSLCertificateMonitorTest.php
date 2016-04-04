<?php

namespace EricMakesStuff\ServerMonitor\Test\Integration\Monitors;

use EricMakesStuff\ServerMonitor\Exceptions\InvalidConfiguration;
use EricMakesStuff\ServerMonitor\Monitors\SSLCertificateMonitor;
use EricMakesStuff\ServerMonitor\Test\Integration\TestCase;

class SSLCertificateMonitorTest extends TestCase
{
    /** @test */
    public function it_can_download_the_certificate_for_a_url_successfully()
    {
        $sslCertificateMonitor = new SSLCertificateMonitor([
            'url' => 'https://www.google.com/',
        ]);

        $sslCertificateMonitor->runMonitor();

        $this->assertGreaterThan(1, $sslCertificateMonitor->getCertificateDaysUntilExpiration());
    }

    /** @test */
    public function it_can_verify_a_wildcard_domain()
    {
        $sslCertificateMonitor = new SSLCertificateMonitor([
            'url' => 'https://lumen.laravel.com/',
        ]);

        $sslCertificateMonitor->runMonitor();

        $this->assertEquals('*.laravel.com', $sslCertificateMonitor->getCertificateDomain());
        $this->assertTrue($sslCertificateMonitor->hostCoveredByCertificate(
            'lumen.laravel.com',
            $sslCertificateMonitor->getCertificateDomain()
        ));
    }

    /** @test */
    public function it_can_handle_a_missing_certificate()
    {
        $sslCertificateMonitor = new SSLCertificateMonitor([
            'url' => 'https://www.implode.com/',
        ]);

        $sslCertificateMonitor->runMonitor();

        $this->assertEmpty($sslCertificateMonitor->getCertificateDomain());
        $this->assertEmpty($sslCertificateMonitor->getCertificateDaysUntilExpiration());
    }

    /** @test */
    public function it_throws_an_exception_for_nonexistent_url()
    {
        $sslCertificateMonitor = new SSLCertificateMonitor([]);

        $this->setExpectedException(InvalidConfiguration::class);

        $sslCertificateMonitor->runMonitor();
    }
}
