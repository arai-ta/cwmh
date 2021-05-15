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
            ->infoStart($this->title())
            ->text($this->url->toMessageLink($this->event))
            ->infoEnd();
    }

    public function __toString(): string
    {
        return $this->render();
    }

    private function title(): string
    {
        return <<<EOT
[piconname:{$this->event->fromAccountId}] -> [piconname:{$this->event->toAccountId}] {$this->event->triggerAction}
EOT;
    }
}
