<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Helpers;
use App\Models\Analytics;
use App\Models\File;
use App\Models\Notifications;
use App\Traits\UserSettings;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;
use App\Models\ImageHelper;
use App\Models\Withdraw;
use App\Traits\RetrieveFirebaseToken;
use App\Models\AccountVerificationRequests;

class ProfileController extends Controller
{
    use RetrieveFirebaseToken;
    use UserSettings;

    protected $User;

    protected $helpers;

    public function __construct(User $User, Helpers $helpers)
    {
        //parent::__construct();
        $this->User    = $User;
        $this->helpers          = $helpers;
    }

    /**
     * Get User profile information
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){

        //$apiUser = Auth::getUser();
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
            'settings' => $this->getUserSettings(),
        ]);
    }


    /**
     * Make profile verified
     */
    public function verify(){

        //$apiUser = Auth::getUser();
        $apiUser = Auth::user();

        $userId = $apiUser->id;


        $userModel = User::find($userId);

        if ( post('basic_information')){
            if ( !is_array( $userModel->basic_information)) $userModel->basic_information = [];
            $userModel->basic_information = array_merge( $userModel->basic_information, post('basic_information'));
        }

        if ( post('specialized_information')){
            if ( !is_array( $userModel->specialized_information)) $userModel->specialized_information = [];
            $userModel->specialized_information = array_merge( $userModel->specialized_information, post('specialized_information'));
        }

        if ( post('financial_information')){
            if ( !is_array( $userModel->financial_information)) $userModel->financial_information = [];
            $userModel->financial_information = array_merge( $userModel->financial_information, post('financial_information'));
        }

        if( !$userModel->save()){
            return $this->helpers->apiArrayResponseBuilder(401, 'error', $userModel->messages()->all());
        }

        $data = $userModel->toArray();

        foreach (['permissions', 'deleted_at', 'updated_at', 'activated_at'] as $v) {
            unset($data[$v]);
        }

        //$data['is_verified'] = 1;
        $verificationRequest = new AccountVerificationRequests;
        $verificationRequest->user_id = $userId;
        $verificationRequest->save();

        //$data['basic_information'] = post('basic_information');
        /*$data['specialized_information'] = post('specialized_information');
        $data['financial_information'] = post('financial_information');*/



        return $this->helpers->apiArrayResponseBuilder(200, 'success', ['user' => $data]);
    }
    /**
     * Set user profile and license image
     */
    public function setImage( $type){

        $file = new File;
        $file->data = Request::file('file');
        $file->is_public = true;
        $file->field = 'cover_image';
        $file->attachment_type = 'App\Models\TestQuestions';
        $data = $file->beforeSave();

        //$apiUser = Auth::getUser();
        $apiUser = Auth::user();

        $helper = new ImageHelper( $apiUser);
        $response = $helper->uploadImage( $type, $data);

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
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPassword(\Illuminate\Http\Request $request){

        //$apiUser = Auth::getUser();
        $apiUser = Auth::user();

        $toValidate = [
            'password' => $request->post('password'),
        ];
        $rules = [
            'password' => 'string|min:4',
        ];

        if ( $request->post('oldPassword')) {

            $isAuthenticated = false;
            try {
                $isAuthenticated = Auth::authenticate([
                    'login' => $apiUser->email,
                    'password' => $request->post('oldPassword'),
                ]);
            } catch(AuthenticationException $e) {
                return $this->helpers->apiArrayResponseBuilder(401, 'error', ['password_not_match']);
            } finally {

            }

            $authenticateStatus = $isAuthenticated ? 'yes' : 'no';

            $toValidate['isAuthenticated'] = $authenticateStatus;
            $rules['isAuthenticated'] = 'accepted';

        }

        $validator = Validator::make($toValidate, $rules);

        if( $validator->fails()){
            return $this->helpers->apiArrayResponseBuilder(401, 'error', $validator->messages()->all());
        }

        $userId = $apiUser->id;

        $user = User::find($userId);
        $user->password = $request->post('password');
        $user->password_confirmation = $request->post('password');
        $user->save();

        $data = User::find($userId)->toArray();

        foreach (['permissions', 'deleted_at', 'updated_at', 'activated_at'] as $v) {
            unset($data[$v]);
        }

        return $this->helpers->apiArrayResponseBuilder(200, 'success', ['user' => $data]);
    }


    /**
     * Set User messaging token
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function token(\Illuminate\Http\Request $request){


        //$apiUser = Auth::getUser();
        $apiUser = Auth::user();

        $model = User::find($apiUser->id);
        $model->messaging_token = $request->post('token');
        $model->save();

        return $this->helpers->apiArrayResponseBuilder(200, 'success');
    }

    /**
     * Submit withdraw from User
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw(){

        //$apiUser = Auth::getUser();
        //$apiUser = Auth::user();

        $model       = User::find(250);
        if ( !$model) return $this->helpers->apiArrayResponseBuilder(401, 'error', ['You must be logged in!']);
        $userBalance = (int) $model->balance;
        $total       = (int) Request::post('total');


        $validator = Validator::make(
            [
                'total' => $total,
                'balance' => $userBalance
            ],
            [

                'total' => 'required|numeric|min:20|max:' . $userBalance,
                'balance' => 'numeric|min:20',
            ],
            [
                'required' => ':attribute_field_is_required',
                'min'      => ':attribute_is_not_enough',
                'max'      => ':attribute_is_too_much',
            ]
        );

        if( $validator->fails()){
            return $this->helpers->apiArrayResponseBuilder(401, 'error', $validator->messages()->all());
        }

        $withdraw = new Withdraw();
        $withdraw->user_id = $apiUser->id;
        $withdraw->total   = post('total');
        $withdraw->comment = post('type');
        $withdraw->type = post('type');

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
     * Admin Accept withdraw
     */
    public function confirm()
    {

        $withdraw = Withdraw::find(get('id'));
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
    public function decline()
    {
        $model = Withdraw::find( get('id'));
        $model->status = Analytics::DECLINED;
        $model->save();

        $user = User::find( $model->user_id);
        $balance = (int) $user->balance;
        $user->balance = $balance + (int) $model->total;
        $user->save();
    }

}
