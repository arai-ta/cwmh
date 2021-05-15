<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kick extends Model
{
    public function hook()
    {
        return $this->belongsTo(Hook::class);
    }
}
