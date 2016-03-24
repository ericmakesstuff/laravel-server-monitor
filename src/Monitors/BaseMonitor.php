<?php

namespace EricMakesStuff\ServerMonitor\Monitors;

abstract class BaseMonitor
{
    public abstract function runMonitor();

    public abstract function __construct(array $config);
}
