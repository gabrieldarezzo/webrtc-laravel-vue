<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\AudioSended;

class Chat extends Model
{
    protected $fillable = [
        'audio',
        'sender',
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
        return $this->orWhere(function ($query) use ($id) {
            $query->where('sender', \Auth::user()->id)
                ->where('receiver', $id);
        })
        ->orWhere(function ($query) use ($id) {
            $query->where('sender', $id)
                ->where('receiver', \Auth::user()->id);
        })
        ->latest()
        ->get();
    }

    public function saveAudio($audio, $receiver)
    {
        $nameAudio = uniqid(date('h-i-s-')) . '.ogg';
        $storeFilePath = public_path() . DIRECTORY_SEPARATOR . 'audios';
        $audio->move($storeFilePath, $nameAudio);

        $dataSave = [
            'audio' => $nameAudio,
            'sender' =>  intval(\Auth::user()->id),
            'receiver' => $receiver,
        ];

        $chat = $this->create($dataSave);
        
        broadcast(new AudioSended($chat));

        return $chat;
    }
    
}
