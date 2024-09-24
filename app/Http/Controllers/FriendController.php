<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{

    public function getAllFriends()
    {
        $friends = new Friend();
        $friends = $friends->getFriends(Auth::user()->id);

        $notFriends = new Friend();
        $notFriends = $notFriends->getNotFriends(Auth::user()->id);

        return view('friends', compact('friends', 'notFriends'));
    }

    public function removeFriend($id)
    {
        $query = new Friend();
        $query::removeFriend(Auth::user()->id, $id);

        // $notFriend = new User();
        // $notFriend = $notFriend->find($id);
        return redirect(route('friends'));

        // return response()->json([
        //     'status' => 'finished',
        //     'notFriend' => [
        //         'name' => $notFriend->name,
        //         'id' => $notFriend->id
        //     ]
        // ]);
    }

    public function addFriend($id)
    {
        $query = new Friend();
        $query::addFriend(Auth::user()->id, $id);

        /*
        $friend = new User();
        $friend = $friend->find($id);
        */

        return redirect(route('friends'));

        /*
        return response()->json([
            'status' => 'success',
            'friend' => [
                'name' => $friend->name,
                'id' => $friend->id
            ]
        ]);
        */
    }

}
