<?php

namespace App\Chatwork;

interface MessageLinkable extends RoomLinkable
{
    public function getMessageId(): string;
}
