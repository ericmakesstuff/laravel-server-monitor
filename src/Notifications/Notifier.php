<?php

namespace EricMakesStuff\ServerMonitor\Notifications;

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
            "Disk Usage on {$this->serverName} is Healthy at {$diskUsageMonitor->getPercentageUsed()} on {$diskUsageMonitor->getPath()}",
            "Disk Usage on {$this->serverName}, filesystem {$diskUsageMonitor->getPath()} is okay: {$diskUsageMonitor->getPercentageUsed()}",
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
            "Disk Usage on {$this->serverName} High! {$diskUsageMonitor->getPercentageUsed()} on {$diskUsageMonitor->getPath()}",
            "Disk Usage on {$this->serverName}, filesystem {$diskUsageMonitor->getPath()} is above the alarm threshold ({$diskUsageMonitor->getAlarmPercentage()}) at {$diskUsageMonitor->getPercentageUsed()}",
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
