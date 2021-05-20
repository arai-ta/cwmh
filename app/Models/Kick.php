<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Hook $hook
 * @property string $result
 * @property string $detail
 */
class Kick extends Model
{
    public function hook()
    {
        return $this->belongsTo(Hook::class);
    }
}
