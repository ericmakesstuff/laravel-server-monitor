<?php

namespace EricMakesStuff\ServerMonitor\Test\Unit\Monitors;

use Carbon\Carbon;
use EricMakesStuff\ServerMonitor\Monitors\SSLCertificateMonitor;

class SSLCertificateMonitorTest extends \PHPUnit_Framework_TestCase
{
    protected $certificate;

    public function setUp()
    {
        $this->certificate = [
            "name" => "/CN=www.example.com",
            "subject" => [
                "CN" => "www.example.com",
            ],
            "hash" => "c12345c6",
            "issuer" => [
                "C" => "US",
                "O" => "Let's Encrypt",
                "CN" => "Let's Encrypt Authority X1",
            ],
            "version" => 2,
            "serialNumber" => "142123456789012345678901234567890123456789",
            "validFrom" => "160219180700Z",
            "validTo" => "160519180700Z",
            "validFrom_time_t" => 1455905220,
            "validTo_time_t" => 1463681220,
            "signatureTypeSN" => "RSA-SHA256",
            "signatureTypeLN" => "sha256WithRSAEncryption",
            "signatureTypeNID" => 668,
            "extensions" => [
                "keyUsage" => "Digital Signature, Key Encipherment",
                "extendedKeyUsage" => "TLS Web Server Authentication, TLS Web Client Authentication",
                "subjectAltName" => "DNS:www.example.org, DNS:example.com, DNS:example.edu, DNS:example.net, DNS:example.org, DNS:www.example.com, DNS:www.example.edu, DNS:www.example.net",
                "basicConstraints" => "CA:FALSE",
                "subjectKeyIdentifier" => "B4:1B:12:34:56:78:90:12:34:56:78:90:12:34:56:78:90:12:34:56",
                "authorityKeyIdentifier" => "keyid:A8:4A:12:34:56:78:90:12:34:56:78:90:12:34:56:78:90:A8:EC:A1\n",
                "authorityInfoAccess" => "OCSP - URI:http://ocsp.int-x1.letsencrypt.org/\nCA Issuers - URI:http://cert.int-x1.letsencrypt.org/\n",
                "certificatePolicies" => "Policy: 2.23.140.1.2.1\nPolicy: 1.3.6.1.4.1.44947.1.1.1\nCPS: http://cps.letsencrypt.org\nUser Notice:\nExplicit Text: This Certificate may only be relied upon by Relying Parties and only in accordance with the Certificate Policy found at https://letsencrypt.org/repository/\n",
            ],
        ];
    }

    /** @test */
    public function it_can_determine_a_host_is_covered_by_a_standard_certificate()
    {
        $monitor = new SSLCertificateMonitor([
            'url' => 'https://www.example.com/',
        ]);

        $this->assertTrue($monitor->hostCoveredByCertificate('www.example.com', 'www.example.com'));
    }

    /** @test */
    public function it_can_determine_a_host_is_not_covered_when_domain_does_not_match()
    {
        $monitor = new SSLCertificateMonitor([
            'url' => 'https://www.example.com/',
        ]);

        $this->assertFalse($monitor->hostCoveredByCertificate('www.example.com', 'www.foobar.com'));
    }

    /** @test */
    public function it_can_determine_a_host_is_covered_by_a_wildcard_certificate()
    {
        $monitor = new SSLCertificateMonitor([
            'url' => 'https://www.example.com/',
        ]);

        $this->assertTrue($monitor->hostCoveredByCertificate('www.example.com', '*.example.com'));
    }

    /** @test */
    public function it_can_determine_a_host_is_not_covered_by_a_non_matching_wildcard_certificate()
    {
        $monitor = new SSLCertificateMonitor([
            'url' => 'https://www.example.com/',
        ]);

        $this->assertFalse($monitor->hostCoveredByCertificate('www.example.com', '*.foobar.com'));
    }

    /** @test */
    public function it_can_determine_a_host_is_covered_by_additional_certificate_domains()
    {
        $monitor = new SSLCertificateMonitor([
            'url' => 'https://www.example.com/',
        ]);

        $this->assertTrue($monitor->hostCoveredByCertificate('www.example.com', 'www.example.org', ['www.example.com', 'www.example.net']));
    }

    /** @test */
    public function it_can_parse_a_certificate()
    {
        $monitor = new SSLCertificateMonitor([
            'url' => 'https://www.example.com/',
        ]);

        $monitor->processCertificate($this->certificate);

        $this->assertEquals('www.example.com', $monitor->getCertificateDomain());
        $this->assertEquals('2016-05-19', $monitor->getCertificateExpiration());
    }

    /** @test */
    public function it_can_parse_a_certificate_additional_domains()
    {
        $monitor = new SSLCertificateMonitor([
            'url' => 'https://www.example.com/',
        ]);

        $monitor->processCertificate($this->certificate);

        $this->assertTrue(in_array('www.example.org', $monitor->getCertificateAdditionalDomains()));
    }

    /** @test */
    public function it_can_parse_a_certificate_days_to_expiration()
    {
        $monitor = new SSLCertificateMonitor([
            'url' => 'https://www.example.com/',
        ]);

        $certificate = $this->certificate;
        $certificate['validTo_time_t'] = strtotime('+2 days');

        $monitor->processCertificate($certificate);

        $this->assertEquals(2, $monitor->getCertificateDaysUntilExpiration());

        $certificate['validTo_time_t'] = strtotime('-2 days');

        $monitor->processCertificate($certificate);

        $this->assertEquals(-2, $monitor->getCertificateDaysUntilExpiration());
    }
}
