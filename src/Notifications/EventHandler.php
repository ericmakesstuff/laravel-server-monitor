<?php

namespace EricMakesStuff\ServerMonitor\Notifications;

use Illuminate\Events\Dispatcher;
use EricMakesStuff\ServerMonitor\Events\DiskUsageAlarm;
use EricMakesStuff\ServerMonitor\Events\DiskUsageHealthy;

class EventHandler
{
    /**
     * @var \EricMakesStuff\ServerMonitor\Notifications\Notifier
     */
    protected $notifier;

    public function __construct()
    {
        $notifierClass = config('server-monitor.notifications.handler');

        $this->notifier = app($notifierClass);
    }

    /**
     * @param \EricMakesStuff\ServerMonitor\Events\DiskUsageAlarm $event
     */
    public function whenDiskUsageAlarm(DiskUsageAlarm $event)
    {
        $this->notifier->diskUsageAlarm($event->diskUsageMonitor);
    }

    /**
     * @param \EricMakesStuff\ServerMonitor\Events\DiskUsageHealthy $event
     */
    public function whenDiskUsageHealthy(DiskUsageHealthy $event)
    {
        $this->notifier->diskUsageHealthy($event->diskUsageMonitor);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     *
     * @return array
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            DiskUsageHealthy::class,
            static::class.'@whenDiskUsageHealthy'
        );

        $events->listen(
            DiskUsageAlarm::class,
            static::class.'@whenDiskUsageAlarm'
        );
    }
}
