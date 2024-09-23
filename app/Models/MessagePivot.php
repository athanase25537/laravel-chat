<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\VarDumper;

class MessagePivot extends Model
{
    use HasFactory;

    protected $fillable = [
        'sms_id',
        'sender_id',
        'receiver_id'
    ];

    public $timestamps = false;

    public function messages() {
        return $this->belongsTo(Message::class);
    }

    public function allMessages($id, $friend_id)
    {

        $sms = DB::select('SELECT * FROM messages as sms
                    INNER JOIN message_pivots as mp
                    WHERE sms.id = mp.sms_id AND
                    ((mp.sender_id = ' . $id . ' AND mp.receiver_id = ' . $friend_id . ') OR (mp.sender_id = ' . $friend_id . ' AND mp.receiver_id = ' . $id . '))
                    ORDER BY sms.updated_at ASC');

        return $sms;
    }

    public function getLastMessagePivot($id, $friend_id, $last_sms_id)
    {
        $sms = DB::select('SELECT * FROM message_pivots
                            WHERE sms_id > ' . $last_sms_id . ' AND (
                            (sender_id = ' . $id . ' AND receiver_id = ' . $friend_id . ') OR
                            (sender_id = ' . $friend_id . ' AND receiver_id = ' . $id . ')
                            ) LIMIT 1');

        return $sms;
    }

    public function getLastMessage($id)
    {
        $sms = DB::select('SELECT content FROM messages WHERE id = ' . $id . ' LIMIT 1');

        return $sms;
    }
}
