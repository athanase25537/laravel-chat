<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Friend extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function friends()
    {
        return $this->belongsToMany(User::class);
    }

    public function getFriends($id)
    {
        $friends = DB::select('SELECT id, name FROM users AS u
                    INNER JOIN friends AS fr WHERE
                    (fr.id_friend1 = ' . $id . ' AND u.id = fr.id_friend2) OR
                    (fr.id_friend2 = ' . $id . ' AND u.id = fr.id_friend1)');

        return $friends;
    }

    public function getNotFriends($id)
    {

        $friends = $this->getFriends($id);
        $userNotFriends = [];
        if($friends !== null) {
            $users = User::all()->where('id', '!=', $id);
            foreach($users as $user) {
                $query = DB::select('SELECT * FROM friends WHERE id_friend1 = ' . $user->id . ' OR id_friend2 = ' . $user->id . '');
                if($query == null) {
                    $userNotFriends[] = $user;
                }
            }
        }
        else $userNotFriends = User::all();

        return $userNotFriends;

    }

    public static function removeFriend($id1, $id2)
    {
        DB::select('DELETE FROM friends WHERE (id_friend1 = ' . $id1 .' AND id_friend2 = ' . $id2 . ') OR (id_friend1 = ' . $id2 .' AND id_friend2 = ' . $id1 . ')');
    }

    public static function addFriend($id1, $id2)
    {
        DB::select('INSERT INTO friends VALUES (' . $id1 . ', ' . $id2 . ')');
    }
}
