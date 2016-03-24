<?php

namespace EricMakesStuff\ServerMonitor\Monitors;

use EricMakesStuff\ServerMonitor\Exceptions\InvalidConfiguration;

class ServerMonitorFactory
{
    /**
     * @param array $monitorConfiguration
     * @return mixed
     * @throws \EricMakesStuff\ServerMonitor\Exceptions\InvalidConfiguration
     */
    public static function createForMonitorConfig(array $monitorConfiguration)
    {
        return collect($monitorConfiguration)->map(function($monitorConfigs, $monitorName) {
            if (file_exists(__DIR__.'/'.ucfirst($monitorName).'Monitor.php')) {
                $className = '\\EricMakesStuff\\ServerMonitor\\Monitors\\'.ucfirst($monitorName).'Monitor';
                return collect($monitorConfigs)->map(function($monitorConfig) use ($className) {
                    return app($className, [$monitorConfig]);
                });
            }

            throw InvalidConfiguration::cannotFindMonitor($monitorName);
        })->flatten();
    }
}
