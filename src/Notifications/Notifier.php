<?php

namespace EricMakesStuff\ServerMonitor\Notifications;

use EricMakesStuff\ServerMonitor\Monitors\HttpPingMonitor;
use EricMakesStuff\ServerMonitor\Monitors\SSLCertificateMonitor;
use Illuminate\Contracts\Logging\Log as LogContract;
use EricMakesStuff\ServerMonitor\Monitors\DiskUsageMonitor;
use Exception;

class Notifier
{
    /** @var array */
    protected $config;

    /** @var \Illuminate\Contracts\Logging\Log */
    protected $log;

    protected $serverName;

    /**
     * @param \Illuminate\Contracts\Logging\Log $log
     */
    public function __construct(LogContract $log)
    {
        $this->log = $log;

        $this->serverName = config('server-monitor.server.name');

        $this->subject = "{$this->serverName} Server Monitoring";
    }

    public function diskUsageHealthy(DiskUsageMonitor $diskUsageMonitor)
    {
        $this->sendNotification(
            'whenDiskUsageHealthy',
            "Disk Usage on {$this->serverName} is Healthy at {$diskUsageMonitor->getPercentageUsed()} Used",
            "Disk Usage is healthy on {$this->serverName}. Filesystem {$diskUsageMonitor->getPath()} is okay: {$diskUsageMonitor->getPercentageUsed()}",
            BaseSender::TYPE_SUCCESS
        );
    }

    /**
     * @param \EricMakesStuff\ServerMonitor\Monitors\DiskUsageMonitor $diskUsageMonitor
     */
    public function diskUsageAlarm(DiskUsageMonitor $diskUsageMonitor)
    {
        $this->sendNotification(
            'whenDiskUsageAlarm',
            "Disk Usage on {$this->serverName} High! {$diskUsageMonitor->getPercentageUsed()} Used",
            "Disk Usage Alarm on {$this->serverName}! Filesystem {$diskUsageMonitor->getPath()} is above the alarm threshold ({$diskUsageMonitor->getAlarmPercentage()}) at {$diskUsageMonitor->getPercentageUsed()}",
            BaseSender::TYPE_ERROR
        );
    }

    /**
     * @param HttpPingMonitor $httpPingMonitor
     */
    public function httpPingUp(HttpPingMonitor $httpPingMonitor)
    {
        $this->sendNotification(
            'whenHttpPingUp',
            "HTTP Ping Success: {$httpPingMonitor->getUrl()}",
            "HTTP Ping Succeeded for {$httpPingMonitor->getUrl()}. Response Code {$httpPingMonitor->getResponseCode()}.",
            BaseSender::TYPE_SUCCESS
        );
    }

    /**
     * @param HttpPingMonitor $httpPingMonitor
     */
    public function httpPingDown(HttpPingMonitor $httpPingMonitor)
    {
        $additionalInfo = '';
        if ($httpPingMonitor->getCheckPhrase() && ! $httpPingMonitor->getResponseContainsPhrase()) {
            $additionalInfo = " Response did not contain \"{$httpPingMonitor->getCheckPhrase()}\"";
        }

        $this->sendNotification(
            'whenHttpPingDown',
            "HTTP Ping Failed: {$httpPingMonitor->getUrl()}!",
            "HTTP Ping Failed for {$httpPingMonitor->getUrl()}! Response Code {$httpPingMonitor->getResponseCode()}.{$additionalInfo}",
            BaseSender::TYPE_ERROR
        );
    }

    /**
     * @param SSLCertificateMonitor $sslCertificateMonitor
     */
    public function sslCertificateValid(SSLCertificateMonitor $sslCertificateMonitor)
    {
        $this->sendNotification(
            'whenSSLCertificateValid',
            "SSL Certificate Valid: {$sslCertificateMonitor->getUrl()}",
            "SSL Certificate is valid for {$sslCertificateMonitor->getUrl()}. Expires in {$sslCertificateMonitor->getCertificateDaysUntilExpiration()} days.",
            BaseSender::TYPE_SUCCESS
        );
    }

    /**
     * @param SSLCertificateMonitor $sslCertificateMonitor
     */
    public function sslCertificateInvalid(SSLCertificateMonitor $sslCertificateMonitor)
    {
        $this->sendNotification(
            'whenSSLCertificateInvalid',
            "SSL Certificate Invalid: {$sslCertificateMonitor->getUrl()}",
            "SSL Certificate is invalid for {$sslCertificateMonitor->getUrl()}. Certificate domain is {$sslCertificateMonitor->getCertificateDomain()}. Certificate expiration date is {$sslCertificateMonitor->getCertificateExpiration()} ({$sslCertificateMonitor->getCertificateDaysUntilExpiration()} days).",
            BaseSender::TYPE_ERROR
        );
    }

    /**
     * @param SSLCertificateMonitor $sslCertificateMonitor
     */
    public function sslCertificateExpiring(SSLCertificateMonitor $sslCertificateMonitor)
    {
        $this->sendNotification(
            'whenSSLCertificateInvalid',
            "SSL Certificate Expiring: {$sslCertificateMonitor->getUrl()}",
            "SSL Certificate for {$sslCertificateMonitor->getUrl()} is expiring on {$sslCertificateMonitor->getCertificateExpiration()} ({$sslCertificateMonitor->getCertificateDaysUntilExpiration()} days).",
            BaseSender::TYPE_ERROR
        );
    }

    /**
     * @param string $eventName
     * @param string $subject
     * @param string $message
     * @param string $type
     */
    protected function sendNotification($eventName, $subject, $message, $type)
    {
        $senderNames = config("server-monitor.notifications.events.{$eventName}");

        collect($senderNames)
            ->map(function ($senderName) {
                $className = $senderName;

                if (file_exists(__DIR__.'/Senders/'.ucfirst($senderName).'.php')) {
                    $className = '\\EricMakesStuff\\ServerMonitor\\Notifications\\Senders\\'.ucfirst($senderName);
                }

                return app($className);
            })
            ->each(function (SendsNotifications $sender) use ($subject, $message, $type) {
                try {
                    $sender
                        ->setSubject($subject)
                        ->setMessage($message)
                        ->setType($type)
                        ->send();
                } catch (Exception $exception) {
                    $errorMessage = "Server Monitor notifier failed because {$exception->getMessage()}"
                        .PHP_EOL
                        .$exception->getTraceAsString();

                    $this->log->error($errorMessage);
                    consoleOutput()->error($errorMessage);
                }
            });
    }
}
