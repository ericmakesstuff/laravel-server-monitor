<?php

namespace EricMakesStuff\ServerMonitor\Notifications\Senders;

use Psr\Log\LoggerInterface as LogContract;
use EricMakesStuff\ServerMonitor\Notifications\BaseSender;

class Log extends BaseSender
{
    /** @var \Psr\Log\LoggerInterface */
    protected $log;

    /**
     * @param \Psr\Log\LoggerInterface $log
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
