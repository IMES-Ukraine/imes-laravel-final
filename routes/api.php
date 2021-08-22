<?php

use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'v1'
    ],
    function () {
        Route::group(
            [
                'prefix' => 'auth'
            ],
            function () {
                /**
                 * WEB application.
                 */
                Route::group(
                    [
                        'prefix' => 'user'
                    ],
                    function () {
                        Route::post('/login',    [UserAuthController::class, 'login']);
                        Route::post('/logout',   [UserAuthController::class, 'logout']);
                        Route::post('/refresh',  [UserAuthController::class, 'refresh']);
                        Route::get ('/profile',  [UserAuthController::class, 'profile']);
                    }
                );
            }
        );
    }
);
