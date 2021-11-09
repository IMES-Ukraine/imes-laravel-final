<?php

use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\CardsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\UsersController;
use App\Http\Controllers\BannersController;
use App\Http\Controllers\UserCardsController;
use App\Http\Controllers\API\ProjectsController as ProjectsApiController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(
    [
        'prefix' => 'api/v1'
    ],
    function () {
        Route::group([
            'prefix' => 'notification'
        ],
            function () {
                Route::post('all', [AdminController::class, 'NotificationSendAll']);

                Route::post('/to', [AdminController::class, 'notificationSendTo']);
            });

        Route::group([
            'prefix' => 'profile'
        ],
            function () {
                Route::get('/verification', [AdminController::class, 'verificationsList']);
                Route::post('/verification/accept', [AdminController::class, 'acceptVerification']);

                Route::post('/verification/decline', [AdminController::class, 'declineVerification']);
            });

        Route::group([
            'prefix' => 'clients'
        ],
            function () {
                Route::get('/', [UsersController::class, 'index']);
                Route::put('/{id}', [UsersController::class, 'update']);

            });

        Route::group([
            'prefix' => 'cards'
        ],
            function () {
                Route::get('/', [CardsController::class, 'index']);
                Route::get('/disable/{id}', [CardsController::class, 'disable']);
                Route::get('/enable/{id}', [CardsController::class, 'enable']);
                Route::post('/', [CardsController::class, 'store']);
                Route::put('/{id}', [CardsController::class, 'update']);
                Route::delete('/{id}', [CardsController::class, 'destroy']);

            });

        Route::group([
            'prefix' => 'banners'
        ],
            function () {
                Route::get('/{type}', [BannersController::class, 'show']);
                Route::post('/', [BannersController::class, 'store']);
            });

        Route::get('/request', [ProfileController::class, 'requests']);

        Route::get('/withdraw', [UserCardsController::class, 'index']);

        Route::group(
            [
                'prefix' => 'project'
            ],
            function () {
                Route::delete('/destroy/{id}', [ProjectsController::class, 'destroy']);
                Route::get('/', [ProjectsController::class, 'index']);
            }
        );
    });
