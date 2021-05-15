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
            url('./config'),
            time()
        );
    }

    public function render(): string
    {
        return (new Message)
            ->infoStart("{$this->appName}")
            ->text($this->appUrl)
            ->infoEnd()
            ->line("ミュートしてピン留めすることを推奨します。")
            ->line("[date:{$this->createdTime}] 作成");
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
