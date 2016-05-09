<?php

use EricMakesStuff\ServerMonitor\Helpers\ConsoleOutput;

/**
 * @return \EricMakesStuff\ServerMonitor\Helpers\ConsoleOutput
 */
function monitorConsoleOutput()
{
    return app(ConsoleOutput::class);
}
