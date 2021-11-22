<?php

namespace App\Http\Controllers;

use App\Models\User;
use Daaner\TurboSMS\Facades\TurboSMS;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'registration']]);
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
     * Get sms
     *
     * @return JsonResponse
     */
    public function sms()
    {
        $phone = request('phone');
        $code = substr(str_shuffle("0123456789"), 0, 6);
        $sended = TurboSMS::sendMessages($phone, 'Enter ' . $code . ' in application', 'sms');
        Session::push('sms', [
            'pnone' => $phone,
            'code' => $code
        ]);

        return response()->json([/*'result' => $sended, */'code' => $code]);
    }
}
