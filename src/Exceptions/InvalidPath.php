<?php

namespace EricMakesStuff\ServerMonitor\Exceptions;

use Exception;

class InvalidPath extends Exception
{
    /**
     * @return \EricMakesStuff\ServerMonitor\Exceptions\InvalidPath
     */
    public static function noPathSpecified()
    {
        return new static('No path was specified to monitor!');
    }

    /**
     * @param $path
     *
     * @return \EricMakesStuff\ServerMonitor\Exceptions\InvalidPath
     */
    public static function pathDoesNotExist($path)
    {
        return new static("This path does not exist: `{$path}`");
    }
}
