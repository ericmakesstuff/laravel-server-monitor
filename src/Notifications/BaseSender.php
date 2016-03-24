<?php

namespace EricMakesStuff\ServerMonitor\Notifications;

abstract class BaseSender implements SendsNotifications
{
    const TYPE_SUCCESS = 'success';
    const TYPE_ERROR = 'error';

    /** @var type */
    protected $type;

    /** @var string */
    protected $subject;

    /** @var string */
    protected $message;

    /**
     * @param string $type
     *
     * @return \EricMakesStuff\ServerMonitor\Notifications\SendsNotifications
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $subject
     *
     * @return \EricMakesStuff\ServerMonitor\Notifications\SendsNotifications
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @param string $message
     *
     * @return \EricMakesStuff\ServerMonitor\Notifications\SendsNotifications
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}
