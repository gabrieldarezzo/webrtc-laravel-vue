<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'audio',
        'snder',
        'receiver',
    ];

    public function sender()
    {
        return $this->hasOne(\App\user::class, 'sender');
    }

    public function receiver()
    {
        return $this->hasOne(\App\user::class, 'receiver');
    }
}
