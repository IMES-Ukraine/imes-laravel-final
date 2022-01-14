<?php

namespace App\Services;

use App\Models\Notifications;
use App\Models\User;
use App\Traits\NotificationsHelper;

class UsersService
{
    public static function setBalance($user_id, $balance)
    {
        $user = User::find($user_id);
        $user->balance = $user->balance + $balance;
        $user->save();

    }

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
