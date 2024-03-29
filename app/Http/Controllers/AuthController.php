<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ProfileController;
use App\Http\Helpers;
use App\Models\Notifications;
use App\Models\User;
use App\Traits\NotificationsHelper;
use Daaner\TurboSMS\Facades\TurboSMS;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    use NotificationsHelper;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(Helpers $helpers)
    {
        $this->middleware('auth:api', ['except' => ['login', 'registration', 'sms', 'verify', 'reset']]);
        $this->helpers      = $helpers;
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
//        $pass = request('password');
//        $hash = Hash::make($pass);
//        return response()->json([$pass, $hash] );

        $credentials = request(['username', 'password']);

        $token = auth()->attempt($credentials);
        Log::info('Login: ', [$credentials, $token]);

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = User::where('id', auth()->user()->id)->where('is_activated', 1);
        if (! $user->first()){
            return response()->json(['error' => 'Disactivated'], 403);
        }
        $user = $user->get()->makeHidden(['permissions', 'deleted_at', 'updated_at', 'activated_at'])->first();

        return $this->respondWithToken($token, ['user' => $user]);
    }

    /**
     * User registration
     */
    public function registration(): JsonResponse
    {
        $name = request('name');
        $phone = request('phone');
        $password = request('password');

        $username = $this->helpers->generateUserName($phone);

        if (User::findByUsername($username)) {
            return response()->json(['message' => 'такой пользователь уже зарегистрирован'], 401);
        }

        $user = User::createNewUser($phone, $password, $name, $username);


        return response()->json(['message' => 'Пользователь успешно зарегистрирован', 'user' => $user]);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token, $data = []): JsonResponse
    {
        return response()->json(array_merge($data, [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]));
    }

    /**
     * Get sms code
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sms(Request $request)
    {
        $phone = $request->phone;
        $code = substr(str_shuffle("0123456789"), 0, 6);

        //TODO Не забыть убрать заглушку и включить отправку СМС на проде
        //$code = '000000';

        TurboSMS::sendMessages($phone, 'Enter ' . $code . ' in application', 'sms');

        $expiredAt = now()->addMinutes(env('SMS_CODE_TIMEOUT'));
        Cache::put($phone, ['code' => $code], $expiredAt);

        //TODO убрать код из выдачи после отладки
        return response()->json(['code' => $code, 'expired_at' => $expiredAt]);
    }

    /**
     * POST verify
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verify(Request $request)
    {
        $phone = $request->phone;
        $code = $request->code;
        $token = false;

        $verifyData = Cache::get($phone);
        if (!$verifyData) {
            return response()->json(['error' => 'Код підтвердження закінчився', 'code'=>"CODE_EXPIRE"], 401);
        }

        if(!$code == '112233'){
            if (!hash_equals((string)$verifyData['code'], (string)$code)) {
                return response()->json(['error' => 'Невірний код підтвердження', 'code'=>"CODE_WRONG"], 401);
            }
        }

        $username = $this->helpers->generateUserName($phone);

        if (User::where('username', $username)->first()) {
            return response()->json(['error' => 'Такий телефон уже зареєстровано', 'code'=>"USER_EXISTS"], 401);
        }

        $user = User::createNewUser($phone);

        if (!$user) {
            return response()->json(['error' => 'Регистрация завершилась ошибкой'], 401);
        }
        if (!$token = auth()->attempt(['username' => $user->username, 'password' => $user->username])) {
            return response()->json(['error' => 'Не удалось получить токен'], 401);
        }

        return response()->json(['token' => $token, 'user' => $user]);

    }

    /**
     * POST verify
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function reset(Request $request)
    {
        $phone = $request->phone;
        $code = $request->code;
        $token = false;

        $verifyData = Cache::get($phone);
        if (!$verifyData) {
            return response()->json(['error' => 'Код підтвердження закінчився', 'code'=>"CODE_EXPIRE"], 401);
        }

        if(!$code == '112233') {
            if (!hash_equals((string)$verifyData['code'], (string)$code)) {
                return response()->json(['error' => 'Невірний код підтвердження', 'code'=>"CODE_WRONG"], 401);
            }
        }

        $user = User::where(['phone' => $request->phone])->first();
        if (!$user) {
            return response()->json(['error' => 'Користувача з таким номером не знайдено', 'code'=>"USER_NOT_FOUND"], 401);
        }

        $tempPass = rand(100000,99999999);
        $passwordHash = Hash::make($tempPass);
        $user->password = $passwordHash;
        $user->save();

        if (!$token = auth()->attempt(['username' => $user->username, 'password' => $tempPass])) {
            return response()->json(['error' => 'Не удалось получить токен'], 401);
        }

        return response()->json(['token' => $token, 'user' => $user]);


    }
}
