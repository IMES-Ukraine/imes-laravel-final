<?php

namespace App\Services;

use App\Models\User;


class UsersService
{
    public static function getBalance($user_id)
    {
        $user = User::find($user_id);

        if ($user) {
            return $user->balance;
        }

        return false;
    }

    public static function reduceBalance($user_id, $balance)
    {
        $user = User::find($user_id);
        $user->balance = ($balance < 0) ? 0 : $balance;
        $user->save();
    }

    public static function getTotal()
    {
        return User::count();
    }

}
