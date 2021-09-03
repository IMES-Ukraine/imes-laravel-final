<?php
namespace App\Models;

use Auth;
use Closure;
use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
/**
 * Checks is the user is banned
 * @package App\Models
 */
class BanMiddleware
{
    protected $app;


    public function handle($request, Closure $next)
    {
        //$apiUser = Auth::getUser();
        $apiUser = Auth::user();
        if ( !isset($apiUser->id)) return response()->make('Forbidden', 403)/*Response::make('Forbidden', 403)*/;
        if ( !$model = User::find($apiUser->id) ) return response()->make('Provided user not found', 403)/*Response::make('Provided user not found', 403)*/;

        return $next($request);
    }
}
