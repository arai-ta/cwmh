<?php

namespace App\Models;

use App\Chatwork\RoomLinkable;
use App\Chatwork\WebhookSettingLinkable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use SunAsterisk\Chatwork\Helpers\Webhook;


/**
 * @property User $user
 * @property string $key
 * @property string $token
 * @property int $webhook_id
 *
 * @property string $target_room_id
 */
class Hook extends Model implements RoomLinkable, WebhookSettingLinkable
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kicks()
    {
        return $this->hasMany(Kick::class);
    }

    public function getRoomId(): int
    {
        return $this->target_room_id;
    }

    public function getWebhookId(): int
    {
        return $this->webhook_id;
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
