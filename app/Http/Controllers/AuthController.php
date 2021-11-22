<?php

namespace App\Http\Controllers;

use App\Models\User;
use Daaner\TurboSMS\Facades\TurboSMS;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'registration', 'sms', 'verify']]);
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

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
//var_dump(User::find(auth()->user()->id) );
//        die;
        return $this->respondWithToken($token, ['user' =>auth()->user()]);
    }

    /**
     * User registration
     */
    public function registration(): JsonResponse
    {
        $name = request('name');
        $email = request('email');
        $password = request('password');

        if (User::findByEmail($email)) {
            return response()->json(['message' => 'Email already registered'], 401);
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->username = request('login') ?? $email;
        $user->password = Hash::make($password);
        $user->save();

        return response()->json(['message' => 'Successfully registration!']);
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
    protected function respondWithToken(string $token, $data=[]): JsonResponse
    {
        return response()->json(array_merge($data, [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ] ));
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
        $sended = TurboSMS::sendMessages($phone, 'Enter ' . $code . ' in application', 'sms');
        Session::put('phone', $phone);
        Session::put('code', $code);

        return response()->json(['code' => $code]);
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

        if (Session::has('phone') && Session::has('code')) {
            if ($phone == Session::get('phone') && $code == Session::get('code')) {
                $password = Hash::make($phone);

                $user = new User();
                $user->phone = $phone;
                $user->username = $phone . '@imes.pro';
                $user->email = $phone . '@imes.pro';
                $user->password = $password;
                $user->save();

                if (!$token = auth()->attempt(['username' => $phone . '@imes.pro', 'password' => $phone])) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }

                Session::remove('phone');
                Session::remove('code');
            }
        }

        return response()->json(['token' => $token]);
    }
}
