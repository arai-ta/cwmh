<?php

namespace App\Chatwork;

class ServiceUrl
{
    private $hostName = "";

    public function __construct(string $hostName)
    {
        $this->hostName = $hostName;
    }

    public static function ofDefault(): self
    {
        return new self('www.chatwork.com');
    }

    public static function KDDIChatwork(): self
    {
        return new self('kcw.kddi.ne.jp');
    }

    public static function create(bool $kddi = false): self
    {
        return $kddi ? self::KDDIChatwork() : self::ofDefault();
    }

    public function roomLink(int $roomId): string
    {
        return $this->getUrl("#!rid{$roomId}");
    }

    public function toRoomLink(RoomLinkable $linkable): string
    {
        return $this->roomLink($linkable->getRoomId());
    }

    public function messageLink(int $roomId, string $messageId): string
    {
        return $this->getUrl("#!rid{$roomId}-{$messageId}");
    }

    public function toMessageLink(MessageLinkable $linkable): string
    {
        return $this->messageLink($linkable->getRoomId(), $linkable->getMessageId());
    }

    public function webhookList(): string
    {
        return $this->getUrl('service/packages/chatwork/subpackages/webhook/list.php');
    }

    public function webhookCreate(): string
    {
        return $this->getUrl('service/packages/chatwork/subpackages/webhook/create.php');
    }

    public function webhookModify(WebhookSettingLinkable $linkable): string
    {
        return $this->getUrl("service/packages/chatwork/subpackages/webhook/modify.php?id={$linkable->getWebhookId()}");
    }

    public function webhookDelete(WebhookSettingLinkable $linkable): string
    {
        return $this->getUrl("service/packages/chatwork/subpackages/webhook/delete.php?id={$linkable->getWebhookId()}");
    }

    public function oauthGrantedApps(): string
    {
        return $this->getUrl("service/packages/chatwork/subpackages/oauth/apps.php");
    }

    private function getUrl(...$path): string
    {
        return 'https://'.$this->hostName.'/'.implode('/', $path);
    }
}
