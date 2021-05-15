<?php

namespace App\Models;

use App\Chatwork\RoomLinkable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use SunAsterisk\Chatwork\Helpers\Webhook;

class Hook extends Model implements RoomLinkable
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kicks()
    {
        return $this->hasMany(Kick::class);
    }

    public function getTargetRoomUrl(): string
    {
        return sprintf('https://www.chatwork.com/#!rid%s', $this->target_room_id);
    }

    public function getRoomId(): int
    {
        return $this->target_room_id;
    }

    public function isValidRequest(Request $request): bool
    {
        return Webhook::verifySignature(
            $this->token,
            $request->getContent(),
            $request->header('X-ChatWorkWebhookSignature')
        );
    }
}
