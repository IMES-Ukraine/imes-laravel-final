<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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



    public function notificationSendTo(Request $request)
    {

        $to = $request->post('to');
        $body = $request->post('body');

        try {

            if (!empty($to)) {

                $user = User::where('username', $to)->first();

                $this->sendNotificationToUser($user, Notifications::TYPE_MESSAGE, $body);


                //Flash::success('Вiдправлено успiшно!');
                Session::flash('success', 'Вiдправлено успiшно!');

            } else {
                throw new Exception('Будь ласка, вкажiть отримувача');
            }

        } catch (Exception $exception) {
            //Flash::error($exception->getMessage());
            Session::flash('error', $exception->getMessage());
        }
    }

    public function verificationsList()
    {
        $requests  = AccountVerificationRequests::with('user')->get();

        return $this->helpers->apiArrayResponseBuilder(
            200,
            'success',
            $requests
        );

    }

    public function acceptVerification(Request $request)
    {
        $user = User::find( $request->post('id') );
        $user->is_verified = 1;
        $user->save();

        $this->declineVerification($request);
        return;

    }

    public function declineVerification(Request $request)
    {
        $request = AccountVerificationRequests::withTrashed()->where( 'user_id', $request->post('id'));
        $request->forceDelete();

        return;
    }

}
