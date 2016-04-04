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
        return new static("No URL Configured.");
    }

    public static function urlNotSecure()
    {
        return new static("URL Not Secure");
    }

    public static function urlCouldNotBeParsed()
    {
        return new static("URL Could Not Be Parsed");
    }

    public static function urlCouldNotBeDownloaded()
    {
        return new static("URL Could Not Be Downloaded");
    }
}
