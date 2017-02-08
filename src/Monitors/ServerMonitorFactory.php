<?php

namespace EricMakesStuff\ServerMonitor\Monitors;

use EricMakesStuff\ServerMonitor\Exceptions\InvalidConfiguration;

class ServerMonitorFactory
{
    /**
     * @param array $monitorConfiguration
     * @param array $filter
     * @return mixed
     */
    public static function createForMonitorConfig(array $monitorConfiguration, array $filter = [])
    {
        $monitors = collect($monitorConfiguration);

        if (count($filter) && !empty($filter[0])) {
            $monitors = $monitors->only($filter);
        }

        $configured_monitors = collect();

        $monitors->map(function($monitorConfigs, $monitorName) {
            if (file_exists(__DIR__.'/'.ucfirst($monitorName).'Monitor.php')) {
                $className = '\\EricMakesStuff\\ServerMonitor\\Monitors\\'.ucfirst($monitorName).'Monitor';
                return collect($monitorConfigs)->map(function($monitorConfig) use ($className) {
                    return new $className($monitorConfig);
                });
            }

            throw InvalidConfiguration::cannotFindMonitor($monitorName);
        })->each(function($monitor_group) use ($configured_monitors) {
            $monitor_group->each(function($monitor) use ($configured_monitors) {
                $configured_monitors->push($monitor);
            });
        });

        return $configured_monitors;
    }
}
