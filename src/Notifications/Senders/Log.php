<?php

namespace EricMakesStuff\ServerMonitor\Notifications\Senders;

use Psr\Log\LoggerInterface as LogContract;
use EricMakesStuff\ServerMonitor\Notifications\BaseSender;

class Log extends BaseSender
{
    /** @var \Illuminate\Contracts\Logging\Log */
    protected $log;

    /**
     * @param \Illuminate\Contracts\Logging\Log $log
     */
    public function __construct(LogContract $log)
    {
        $this->log = $log;
    }

    public function send()
    {
        $method = ($this->type === static::TYPE_SUCCESS ? 'info' : 'error');

        $this->log->$method("{$this->subject}: {$this->message}");
    }
}
