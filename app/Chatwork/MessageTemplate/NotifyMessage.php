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

    /** @var string */
    private $roomName;

    public function __construct(MentionToMeEvent $event, ServiceUrl $url, string $roomName)
    {
        $this->event    = $event;
        $this->url      = $url;
        $this->roomName = $roomName;
    }

    public function render(): string
    {
        return (new Message)
            ->infoStart($this->title())
            ->text($this->url->toMessageLink($this->event))
            ->infoEnd();
    }

    private function title(): string
    {
        return <<<EOT
{$this->event->triggerAction} by [piconname:{$this->event->fromAccountId}] @ {$this->roomName}
EOT;
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
