<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hook extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTargetRoomUrl(): string
    {
        return sprintf('https://www.chatwork.com/#!rid%s', $this->target_room_id);
    }
}
