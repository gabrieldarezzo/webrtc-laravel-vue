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

    public function getAllByMeAnd($id)
    {
        return $this->orWhere(function($query) use($id) {
            $query->where('sender', \Auth::user()->id)
                ->where('receiver', $id);
        })
        ->orWhere(function($query) use($id) {
            $query
                ->where('receiver', $id)
                ->where('sender', \Auth::user()->id);
        })
        ->latest()
        ->get();
    }



    
}
