<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Authenticate extends Middleware
{

    public function terminate(Request $request, JsonResponse $response)

    {
        Log::info('Api request', [
            'uri' => $request->url(),
            'data' => $request->input(),
            'response' => json_decode($response->content(), true )
        ]);

    }



    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {

        if (! auth()->user()) {
            throw new HttpException(403, 'Неверные данные авторизации');

//            return route('login');
        }
    }

}
