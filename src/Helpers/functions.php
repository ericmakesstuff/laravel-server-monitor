<?php

use EricMakesStuff\ServerMonitor\Helpers\ConsoleOutput;

/**
 * @return \EricMakesStuff\ServerMonitor\Helpers\ConsoleOutput
 */
function consoleOutput()
{
    return app(ConsoleOutput::class);
}
