<?php

namespace App\Http\Controllers;

use App\Events\PrivateEvent;
use App\Models\Friend;
use App\Models\Message;
use App\Models\MessagePivot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Mime\Part\MessagePart;

class MessageController extends Controller
{

    public function getMessages(Request $request)
    {

        $sms = new MessagePivot();
        $sms = $sms->allMessages(Auth::user()->id, $request->id);
        $friend = new User();
        $friend = $friend->find($request->id);

        $last = (count($sms)!==0) ? $sms[count($sms)-1]->id : 0;

        return view('chats', compact('sms', 'friend', 'last'));
    }

    public function post(Request $request)
    {

        $sms = new Message();
        $sms->content = $request->sms;
        $sms->save();

        $smsPivot = new MessagePivot();
        $smsPivot->sms_id = $sms->id;
        $smsPivot->sender_id = Auth::user()->id;
        $smsPivot->receiver_id = $request->id;
        $smsPivot->save();

        event(new PrivateEvent($sms->content, $request->id));

        return response()->json([
            'sms' => $request->sms,
            'last' => $sms->id
        ]);

    }


}
