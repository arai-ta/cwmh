<?php

namespace App\Chatwork\Webhook;

use App\Chatwork\MessageLinkable;
use function Symfony\Component\Translation\t;

/**
 * @property-read int fromAccountId
 * @property-read int toAccountId
 * @property-read int roomId
 * @property-read string messageId
 * @property-read string body
 * @property-read int sendTime
 * @property-read int updateTime
 * @property-read string triggerAction
 */
class MentionToMeEvent implements MessageLinkable
{
    private $fromAccountId = 0;
    private $toAccountId = 0;
    private $roomId = 0;
    private $messageId = "";
    private $body = "";
    private $sendTime = 0;
    private $updateTime = 0;
    private $triggerAction = "";

    public function __construct(int $fromAccountId, int $toAccountId, int $roomId, string $messageId, string $body, int $sendTime, int $updateTime)
    {
        $this->fromAccountId = $fromAccountId;
        $this->toAccountId = $toAccountId;
        $this->roomId = $roomId;
        $this->messageId = $messageId;
        $this->body = $body;
        $this->sendTime = $sendTime;
        $this->updateTime = $updateTime;

        $this->guessTriggerAction($this->body);
    }

    public static function fromJsonString(string $json): self
    {
        $ev = json_decode($json)->webhook_event;

        return new self(
            $ev->from_account_id,
            $ev->to_account_id,
            $ev->room_id,
            $ev->message_id,
            $ev->body,
            $ev->send_time,
            $ev->update_time
        );
    }

    public function __get($name)
    {
        if (!property_exists($this, $name)) {
            throw new \OutOfRangeException("{$name} is not defined");
        }

        return $this->{$name};
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function getRoomId(): int
    {
        return $this->roomId;
    }

    private function guessTriggerAction(string $body)
    {
        if (stripos($body, "[To:{$this->toAccountId}]") !== false) {
            $this->triggerAction = "🔜 TO";
        } elseif (stripos($body, "[rp aid={$this->toAccountId}") !== false) {
            $this->triggerAction = "🔙 REPLY";
        } elseif (stripos($body, "[task aid={$this->toAccountId}") !== false) {
            $this->triggerAction = "📝 TASK";
        } elseif (stripos($body, "[toall]") !== false) {
            $this->triggerAction = "📣️ TOALL";
        } else {
            $this->triggerAction = "👥 DIRECT";
        }
    }

}
