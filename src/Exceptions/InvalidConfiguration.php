<?php

namespace EricMakesStuff\ServerMonitor\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    /**
     * @param $monitorName
     * @return InvalidConfiguration
     */
    public static function cannotFindMonitor($monitorName)
    {
        return new static("Could not find monitor named `{$monitorName}`.");
    }
    
    public static function noUrlConfigured()
    {
        return new static ("No URL Configured.");
    }
}
