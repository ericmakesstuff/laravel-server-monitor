<?php

namespace EricMakesStuff\ServerMonitor\Notifications\Senders;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;
use EricMakesStuff\ServerMonitor\Notifications\BaseSender;

class Mail extends BaseSender
{
    /** @var Mailer */
    protected $mailer;

    /** @var array */
    protected $config;

    /**
     * @param Mailer     $mailer
     * @param Repository $config
     */
    public function __construct(Mailer $mailer, Repository $config)
    {
        $this->config = $config->get('server-monitor.notifications.mail');

        $this->mailer = $mailer;
    }

    public function send()
    {
        $this->mailer->raw($this->message, function (Message $message) {
          foreach ($this->config['to'] as $mailTo) {
            $message
              ->subject($this->subject)
              ->from($this->config['from'])
              ->to($mailTo);
          }
        });
    }
}
