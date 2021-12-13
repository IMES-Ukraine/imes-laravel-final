<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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

                if ($user) {
                    $this->sendNotificationToUser($user, Notifications::TYPE_MESSAGE, $body, $extraFields, $title);

                    Session::flash('success', 'Вiдправлено успiшно!');
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
        $requests  = AccountVerificationRequests::with('user')->get()->toArray();

        return $this->helpers->apiArrayResponseBuilder(
            200,
            'success',
            $requests
        );

    }

    public function acceptVerification($id): JsonResponse
    {
        $user = User::find( $id );
        $user->is_verified = 1;
        $user->save();

        // после подтверждения удаляем заявку из таблицы заявок
        $this->declineVerification($id);
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $user->toArray() );

    }

    public function declineVerification($id)
    {
        AccountVerificationRequests::where( ['user_id' =>  $id])->delete();
    }

}
