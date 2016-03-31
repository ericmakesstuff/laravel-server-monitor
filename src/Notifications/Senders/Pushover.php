<?php

namespace EricMakesStuff\ServerMonitor\Notifications\Senders;

use Illuminate\Contracts\Config\Repository;
use EricMakesStuff\ServerMonitor\Notifications\BaseSender;

class Pushover extends BaseSender
{
    /** @var array */
    protected $config;

    /**
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config->get('server-monitor.notifications.pushover');
    }

    public function send()
    {
        curl_setopt_array($ch = curl_init(), [
            CURLOPT_URL => 'https://api.pushover.net/1/messages.json',
            CURLOPT_POSTFIELDS => [
                'token' => $this->config['token'],
                'user' => $this->config['user'],
                'title' => $this->subject,
                'message' => $this->message,
                'sound' => $this->type === static::TYPE_SUCCESS ? 'pushover' : 'siren',
            ],
            CURLOPT_SAFE_UPLOAD => true,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}
