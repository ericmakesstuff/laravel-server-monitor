<?php

namespace EricMakesStuff\ServerMonitor\Exceptions;

use Exception;

class InvalidCommand extends Exception
{
    /**
     * @param string $reason
     *
     * @return \EricMakesStuff\ServerMonitor\Exceptions\InvalidCommand
     */
    public static function create($reason)
    {
        return new static($reason);
    }
}
