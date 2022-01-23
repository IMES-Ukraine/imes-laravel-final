<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Projects;
use App\Services\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\Flysystem\Exception;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Notifications;
use App\Traits\NotificationsHelper;
use App\Http\Helpers;
use App\Models\AccountVerificationRequests;
use Illuminate\Routing\Controller as BaseController;


class AdminController extends BaseController
{
    use NotificationsHelper;

    public $helpers;

    public function __construct(Helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    public function NotificationSendAll(Request $request)
    {
        $body = $request->post('body');
        $title = $request->post('title');
        $action = $request->post('action');
        $extraFields = [];

        if ($action) {
            $extraFields['action'] = $action;
        }

        try {

            $this->sendNotificationToAll(Notifications::TYPE_MESSAGE, $body, $extraFields, $title);

            Session::flash('success', 'Вiдправлено успiшно!');

        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        }
    }

    public function notificationSendTo(Request $request)
    {

        $to = $request->post('to');
        $body = $request->post('body');
        $title = $request->post('title');
        $action = $request->post('action');
        $extraFields = [];

        if ($action) {
            $extraFields['action'] = $action;
        }

        try {

            if (!empty($to)) {

                $user = User::where('id', $to)->whereNotNull('firebase_token')->first();

                if ($user && $user->firebase_token) {
                    if (strlen($user->firebase_token > 163)) {
                        $user->firebase_token = null;
                    } else {
                        $this->sendNotificationToUser($user, Notifications::TYPE_MESSAGE, $body, $extraFields, $title);

                        Session::flash('success', 'Вiдправлено успiшно!');
                    }
                } else {
                    Session::flash('error', 'Firebase Token Error');
                }

            } else {
                throw new Exception('Будь ласка, вкажiть отримувача');
            }

        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        }
    }

    public function verificationsList()
    {
        $requests = AccountVerificationRequests::with('user')->get()->toArray();

        return $this->helpers->apiArrayResponseBuilder(
            200,
            'success',
            $requests
        );

    }

    public function acceptVerification($id): JsonResponse
    {
        $user = User::find($id);
        $user->is_verified = User::USER_IS_VERIFIED_TRUE;
        $user->save();

        $projectList = Projects::whereNull('deleted_at')->get();
        UsersService::checkUserTargeting($id, $projectList);

        $this->sendNotificationToUser($user, Notifications::TYPE_MESSAGE,
            'Ваши учётные данные проверены и приняты модератором. Учетная запись получила статус верифицированной', [],
            'Данные верифицированы');

        // после подтверждения удаляем заявку из таблицы заявок
        AccountVerificationRequests::where(['user_id' => $id])->delete();
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $user->toArray());

    }

    public function declineVerification($id)
    {
        $user = User::find($id);
        $this->sendNotificationToUser($user, Notifications::TYPE_MESSAGE,
            'Ваши учётные данные проверены и отклонены модератором. По всем вопросам обращайтесь в службу поддержки', [],
            'Данные отклонены');
        AccountVerificationRequests::where(['user_id' => $id])->delete();
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $user->toArray());
    }

}
