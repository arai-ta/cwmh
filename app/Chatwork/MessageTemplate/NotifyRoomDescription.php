<?php

namespace App\Chatwork\MessageTemplate;

use SunAsterisk\Chatwork\Helpers\Message;

class NotifyRoomDescription
{
    private $appName = '';
    private $appUrl = '';
    private $createdTime = 0;

    public function __construct(string $appName, string $appUrl, int $createdTime)
    {
        $this->appName      = $appName;
        $this->appUrl       = $appUrl;
        $this->createdTime  = $createdTime;
    }

    public static function create(): self
    {
        return new self(
            env('APP_NAME'),
            url('config', [], true),
            time()
        );
    }

    public function render(): string
    {
        return (new Message)
            ->infoStart("{$this->appName}")
            ->line("設定: ".$this->appUrl)
            ->text("作成: [date:{$this->createdTime}]")
            ->infoEnd()
            ->text("ミュートしてピン留めすることを推奨します。");
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
