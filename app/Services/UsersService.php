<?php
namespace App\Services;

use App\Models\User;

class UsersService
{
    public static function setBalance($user_id, $balance) {
        $user = User::find($user_id);
        $user->balance = $user->balance + $balance;
        $user->save();
    }

    public static function getBalance($user_id) {
        $user = User::find($user_id);

        if ($user) return $user->balance;

        return false;
    }

}
