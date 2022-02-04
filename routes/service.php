<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Service API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'api',
], function () {
    Route::post('auth', [AuthController::class, 'sms']);
    Route::post('verify', [AuthController::class, 'verify']);
    Route::post('reset', [AuthController::class, 'reset']);
});
