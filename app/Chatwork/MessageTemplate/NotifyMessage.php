<?php

namespace App\Chatwork\MessageTemplate;

use App\Chatwork\ServiceUrl;
use App\Chatwork\Webhook\MentionToMeEvent;
use SunAsterisk\Chatwork\Helpers\Message;

class NotifyMessage
{
    /** @var MentionToMeEvent */
    private $event;

    /** @var ServiceUrl */
    private $url;

    public function __construct(MentionToMeEvent $event, ServiceUrl $url)
    {
        $this->event = $event;
        $this->url = $url;
    }

    public function render(): string
    {
        return (new Message)
            ->infoStart("{$this->event->triggerAction} by [piconname:{$this->event->fromAccountId}]")
            ->text($this->url->toMessageLink($this->event))
            ->infoEnd();
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
