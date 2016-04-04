<?php

namespace EricMakesStuff\ServerMonitor\Monitors;

use Carbon\Carbon;
use EricMakesStuff\ServerMonitor\Events\SSLCertificateExpiring;
use EricMakesStuff\ServerMonitor\Events\SSLCertificateInvalid;
use EricMakesStuff\ServerMonitor\Events\SSLCertificateValid;
use EricMakesStuff\ServerMonitor\Exceptions\InvalidConfiguration;

class SSLCertificateMonitor extends BaseMonitor
{
    /**  @var array */
    protected $certificateInfo;

    /**  @var string */
    protected $certificateExpiration;

    /**  @var string */
    protected $certificateDomain;

    /**  @var array */
    protected $certificateAdditionalDomains = [];

    /**  @var int */
    protected $certificateDaysUntilExpiration;

    /**  @var string */
    protected $url;

    /**  @var array */
    protected $alarmDaysBeforeExpiration = [28, 14, 7, 3, 2, 1, 0];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (!empty($config['url'])) {
            $this->url = $config['url'];
        }

        if (!empty($config['alarmDaysBeforeExpiration'])) {
            $this->alarmDaysBeforeExpiration = $config['alarmDaysBeforeExpiration'];
        }
    }

    /**
     * @throws InvalidConfiguration
     */
    public function runMonitor()
    {
        $urlParts = $this->parseUrl($this->url);

        try {
            $this->certificateInfo = $this->downloadCertificate($urlParts);
        } catch (\ErrorException $e) {
            event(new SSLCertificateInvalid($this));
            return false;
        } catch (\Exception $e) {
            throw InvalidConfiguration::urlCouldNotBeDownloaded();
        }

        $this->processCertificate($this->certificateInfo);

        if ($this->certificateDaysUntilExpiration < 0
            || ! $this->hostCoveredByCertificate($urlParts['host'], $this->certificateDomain, $this->certificateAdditionalDomains)) {
            event(new SSLCertificateInvalid($this));
        } elseif (in_array($this->certificateDaysUntilExpiration, $this->alarmDaysBeforeExpiration)) {
            event(new SSLCertificateExpiring($this));
        } else {
            event(new SSLCertificateValid($this));
        }
    }

    protected function downloadCertificate($urlParts)
    {
        $streamContext = stream_context_create([
            "ssl" => [
                "capture_peer_cert" => TRUE
            ]
        ]);

        $streamClient = stream_socket_client("ssl://{$urlParts['host']}:443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $streamContext);

        $certificateContext = stream_context_get_params($streamClient);

        return openssl_x509_parse($certificateContext['options']['ssl']['peer_certificate']);
    }

    public function processCertificate($certificateInfo)
    {
        if (!empty($certificateInfo['subject']) && !empty($certificateInfo['subject']['CN'])) {
            $this->certificateDomain = $certificateInfo['subject']['CN'];
        }

        if (!empty($certificateInfo['validTo_time_t'])) {
            $validTo = Carbon::createFromTimestampUTC($certificateInfo['validTo_time_t']);
            $this->certificateExpiration = $validTo->toDateString();
            $this->certificateDaysUntilExpiration = - $validTo->diffInDays(Carbon::now(), false);
        }

        if (!empty($certificateInfo['extensions']) && !empty($certificateInfo['extensions']['subjectAltName'])) {
            $this->certificateAdditionalDomains = [];
            $domains = explode(', ', $certificateInfo['extensions']['subjectAltName']);
            foreach ($domains as $domain) {
                $this->certificateAdditionalDomains[] = str_replace('DNS:', '', $domain);
            }
        }
    }

    public function hostCoveredByCertificate($host, $certificateHost, array $certificateAdditionalDomains = [])
    {
        if ($host == $certificateHost) {
            return true;
        }

        // Determine if wildcard domain covers the host domain
        if ($certificateHost[0] == '*' && substr_count($host, '.') > 1) {
            $certificateHost = substr($certificateHost, 1);
            $host = substr($host, strpos($host, '.'));
            return $certificateHost == $host;
        }

        // Determine if the host domain is in the certificate's additional domains
        return in_array($host, $certificateAdditionalDomains);
    }

    protected function parseUrl($url)
    {
        if (empty($url)) {
            throw InvalidConfiguration::noUrlConfigured();
        }

        $urlParts = parse_url($url);

        if (!$urlParts) {
            throw InvalidConfiguration::urlCouldNotBeParsed();
        }

        if (empty($urlParts['scheme']) || $urlParts['scheme'] != 'https') {
            throw InvalidConfiguration::urlNotSecure();
        }

        return $urlParts;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getCertificateInfo()
    {
        return $this->certificateInfo;
    }

    public function getCertificateExpiration()
    {
        return $this->certificateExpiration;
    }

    public function getCertificateDomain()
    {
        return $this->certificateDomain;
    }

    public function getCertificateDaysUntilExpiration()
    {
        return $this->certificateDaysUntilExpiration;
    }

    public function getAlarmDaysBeforeExpiration()
    {
        return $this->alarmDaysBeforeExpiration;
    }

    public function getCertificateAdditionalDomains()
    {
        return $this->certificateAdditionalDomains;
    }
}
