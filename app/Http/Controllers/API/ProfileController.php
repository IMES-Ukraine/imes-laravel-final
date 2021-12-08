<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Helpers;
use App\Models\AccountRequests;
use App\Models\Analytics;
use App\Models\File;
use App\Models\Notifications;
use App\Traits\NotificationsHelper;
use App\Traits\UserSettings;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\ImageHelper;
use App\Models\Withdraw;
use App\Traits\RetrieveFirebaseToken;
use App\Models\AccountVerificationRequests;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use function Symfony\Component\Translation\t;

class ProfileController extends Controller
{
    use RetrieveFirebaseToken;
    use UserSettings;
    use NotificationsHelper;

    protected $User;
    protected $helpers;

    public function __construct(User $User, Helpers $helpers)
    {
        $this->User         = $User;
        $this->helpers      = $helpers;
    }

    /**
     * Get User profile information
     * @return JsonResponse
     */
    public function index(){

        $apiUser = Auth::user();

        $user = User::find($apiUser->id);
        $user->firebase_token = $this->retrieveFirebaseToken();
        $user->save();
        $data = $user->toArray();

        foreach (['permissions', 'deleted_at', 'updated_at', 'activated_at'] as $v) {
            unset($data[$v]);
        }

        //$data['firebase_token'] = $this->retrieveFirebaseToken();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', [
            'user' => $data,
        //    'settings' => $this->getUserSettings(),
        ]);
    }


    /**
     * send request for verifying profile
     */
    public function verify(Request $request){

        $userModel = auth()->user();

        if ($userModel->is_verified){
            return $this->helpers->apiArrayResponseBuilder(201, 'Пользователь уже верифицирован');
        }

        if (AccountVerificationRequests::where('user_id', $userModel->id)->first() ){
            return $this->helpers->apiArrayResponseBuilder(201, 'Заявка от этого пользователя уже подана');
        }

        if ( $request->post('basic_information')){
            if ( !is_array( $userModel->basic_information)) $userModel->basic_information = [];
            $userModel->basic_information = array_merge( $userModel->basic_information, $request->post('basic_information'));
        }

        if ( $request->post('specialized_information')){
            if ( !is_array( $userModel->specialized_information)) $userModel->specialized_information = [];
            $userModel->specialized_information = array_merge( $userModel->specialized_information, $request->post('specialized_information'));
        }

        if ( $request->post('financial_information')){
            if ( !is_array( $userModel->financial_information)) $userModel->financial_information = [];
            $userModel->financial_information = array_merge( $userModel->financial_information, $request->post('financial_information'));
        }

        if( !$userModel->save()){
            return $this->helpers->apiArrayResponseBuilder(401, 'error', $userModel->messages()->all());
        }

        $data = $userModel->makeHidden(['permissions', 'deleted_at', 'updated_at', 'activated_at'])->toArray();


        $verificationRequest = new AccountVerificationRequests;
        $verificationRequest->user_id = $userModel->id;
        $verificationRequest->save();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', ['user' => $data]);
    }
    /**
     * Set user profile and license image
     */
    public function setImage( $type){

        $file = new File;
        $file->data = request()->file('file');
        $file->is_public = true;
        $file->field = $type;
        $file->attachment_type = User::class;
        $data = $file->beforeSave();

        /** @var User $apiUser */
        $apiUser = auth()->user();

        $helper = new ImageHelper( $apiUser);
        $response = $helper->uploadImage( $type, $file);

        if( !$response){
            return $this->helpers->apiArrayResponseBuilder(401, 'error', []);
        }

        $arr = [
            'status_code' => 200,
            'message' => 'success',
            'data' => $file
        ];
        return response()->json($arr, 200);
        //return $this->helpers->apiArrayResponseBuilder(200, 'success', [$file]);
    }


    /**
     * Do set User profile password
     * @return JsonResponse
     */
    public function setPassword(Request $request){

        //$apiUser = Auth::getUser();
        $apiUser = auth()->user();

        $toValidate = [
            'password' => $request->post('password'),
        ];
        $rules = [
            'password' => 'string|min:4',
        ];

        $validator = Validator::make($toValidate, $rules);

        if( $validator->fails()){
            return $this->helpers->apiArrayResponseBuilder(401, $validator->getMessageBag());
        }

        $apiUser->update(['password' => Hash::make($request->post('password') )]);

        $data = User::where(['id' => $apiUser->id])->first()->makeHidden(['permissions', 'deleted_at', 'updated_at', 'activated_at'])->toArray();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', ['user' => $data]);
    }


    /**
     * Set User messaging token
     * @param Request $request
     * @return JsonResponse
     */
    public function token(Request $request){

        $apiUser = Auth::user();

        $model = User::find($apiUser->id);
        $model->messaging_token = $request->input('token');
        $model->save();

        return $this->helpers->apiArrayResponseBuilder(200, 'success');
    }

    /**
     * Submit withdraw from User
     * @return JsonResponse
     */
    public function withdraw(Request $request){

        $apiUser = \auth()->user();

        $model       = User::find($apiUser->id);
        if ( !$model) return $this->helpers->apiArrayResponseBuilder(401, 'error', ['You must be logged in!']);
        $userBalance = (int) $model->balance;
        $total       = (int) $request->post('total');
        $type = $request->post('type');


        $validator = Validator::make(
            [
                'total' => $total,
                'type' => $type,
                'balance' => $userBalance
            ],
            [

                'total' => 'required|numeric|min:20|max:' . $userBalance,
                'type' => 'required',
                'balance' => 'numeric|min:20',
            ],
            [
                'required' => ':attribute ' . t('field_is_required'),
                'min'      => ':attribute ' . t('is_not_enough'),
                'max'      => ':attribute is_too_much',
            ]
        );

        if( $validator->fails()){
            return $this->helpers->apiArrayResponseBuilder(401, 'error', $validator->messages()->all());
        }

        $withdraw = new Withdraw();
        $withdraw->user_id = $apiUser->id;
        $withdraw->total   = $total;
        $withdraw->comment = $type;
        $withdraw->type = $request->post('type');

        $withdraw->save();

        $model->balance = $userBalance - $total;

        if( !$model->save()){
            return $this->helpers->apiArrayResponseBuilder(401, 'error', $model->messages()->all());
        }

        $data = User::find($apiUser->id)->toArray();

        foreach (['permissions', 'deleted_at', 'updated_at', 'activated_at'] as $v) {
            unset($data[$v]);
        }


        return $this->helpers->apiArrayResponseBuilder(200, 'success', ['data' => 'withdrawal_submitted', 'user' => $data]);
    }

    /*
     * Admin Lists fresh withdraws
     */
    public function withdraws()
    {
        $withdraws = Withdraw::where('status', Analytics::IN_PROGRESS)
            ->with('user')
            ->orderBy('created_at', 'desc')->get();

        return $this->helpers->apiArrayResponseBuilder(
            200,
            'success',
            $withdraws);
    }

    /*
     * Admin Lists fresh requests
     */
    public function requests()
    {
        $requests = AccountRequests::with('userCity')
            ->with('userWork')
            ->orderBy('created_at', 'desc')
            ->get()->toArray();

        return $this->helpers->apiArrayResponseBuilder(
            200,
            'success',
            $requests);
    }

    /*
     * Admin Accept withdraw
     */
    public function confirm($id)
    {
        $withdraw = Withdraw::find($id);
        $withdraw->status = Analytics::APPROVED;
        $withdraw->save();

        $user = User::find( $withdraw->user_id);

        $this->sendNotificationToUser(
            $user, Notifications::TYPE_WITHDRAW,
            'Переказ '. $withdraw->total.' балiв виконано',
            [
                'balance' => (int) $user->balance
            ]);
    }

    /*
     * Admin Decline withdraw
     */
    public function decline($id)
    {
        $model = Withdraw::find($id);
        $model->status = Analytics::DECLINED;
        $model->save();

        $user = User::find( $model->user_id);
        $balance = (int) $user->balance;
        $user->balance = $balance + (int) $model->total;
        $user->save();
    }

    /**
     * Confirm user registration from admin panel
     * @return mixed
     */
    public function confirmRequest($id)
    {
        $request = AccountRequests::findOrFail($id);

        $password = Str::lower(Str::random(8));

        $params = [
            'email' => $request['email'],
            'name' => $request['name'],
            'phone' => $request['phone'],
            'city' => $request['city'],
            'work' => $request['work'],
            'password' => $password,
            'password_confirmation' => $password,
        ];

        /*$user = Auth::register($params);

        $user->convertToRegistered(false);*/

        $savedUser = User::findByEmail($params['email']);
        $savedUser->work = $params['work'];
        $savedUser->city = $params['city'];
        $savedUser->phone = $params['phone'];
        $savedUser->username = $this->generateUserId( $savedUser->id);
        $savedUser->balance = 0;
        $savedUser->company_id = $request['company_id'];
        $savedUser->is_activated = 1;

        $savedUser->save();
        $request->delete();

        try {
            //Mail::sendTo($user, 'ulogic.registration::mail.new_user', $params);
            Mail::send('emails.email', ['data' => $params], function ($message) use ($request) {
                $message->subject('Запросы');
                $message->to($request->email, $request->name);
            });
        } catch (\Exception $exception) {

        }
    }

    /**
     * Confirm user registration from admin panel
     * @return mixed
     */
    public function declineRequest($id)
    {
        $request = AccountRequests::findOrFail($id);

        try {
            $request->delete();
        } catch (\Exception $exception) {

        }
    }

    protected function generateUserId( $id) {
        return 100000 + (int)$id;
    }

    public function onAcceptVerification($id)
    {
        $user = User::find($id);
        $user->is_verified = 1;
        $user->save();

        $request = AccountVerificationRequests::where( 'user_id', $id);
        $request->delete();
    }

    public function onDeclineVerification($id)
    {
        $request = AccountVerificationRequests::where( 'user_id', $id);
        $request->delete();

    }

}
