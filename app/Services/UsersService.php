<?php

namespace App\Services;

use App\Models\Projects;
use App\Models\User;


class UsersService
{
    const USER_ALREADY_IN_PROJECT = 1;
    const USER_NOT_IN_PROJECT = 2;
    const USER_MUST_BE_ADDED_TO_PROJECT = 3;
    const USER_MUST_BE_DELETED_FROM_PROJECT = 4;


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

    public static function checkUserTargeting($user_id, $projectList)
    {
        $data = [];
        foreach ($projectList as $project) {
            $target = $project->audience;

            // TODO здесь потом будут проверки категорий и места. Пока просто добавляем юзера.
            if (!in_array($user_id, $target)) {
                array_push($target, (int)$user_id);
                $data[] = [
                    'id'       => $project->id,
                    'audience' => json_encode($target)
                ];
            }
        }
        // выполняем массовое обновление одним запросом.
        Projects::upsert($data, ['id'], ['audience']);
    }

}
