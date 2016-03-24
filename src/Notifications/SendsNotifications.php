<?php

namespace EricMakesStuff\ServerMonitor\Notifications;

interface SendsNotifications
{
    /**
     * @param string $type
     *
     * @return \EricMakesStuff\ServerMonitor\Notifications\SendsNotifications
     */
    public function setType($type);

    /**
     * @param string $subject
     *
     * @return \EricMakesStuff\ServerMonitor\Notifications\SendsNotifications
     */
    public function setSubject($subject);

    /**
     * @param string $message
     *
     * @return \EricMakesStuff\ServerMonitor\Notifications\SendsNotifications
     */
    public function setMessage($message);

    public function send();
}
