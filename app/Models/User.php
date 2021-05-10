<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string token
 */
class User extends Model
{
    protected $fillable = [
        'account_id',
        'token',
    ];

    public function tokenAsArray(): array
    {
        return json_decode($this->token, true);
    }

    public function hook()
    {
        return $this->hasOne(Hook::class);
    }
}
