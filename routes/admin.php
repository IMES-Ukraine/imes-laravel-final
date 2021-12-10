<?php

use App\Http\Components\Filter;
use App\Http\Controllers\API\AnalyticsController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\ModerationController;
use App\Http\Controllers\API\NotificationsController;
use App\Http\Controllers\API\TestsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\CardsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\UsersController;
use App\Http\Controllers\BannersController;
use App\Http\Controllers\UserCardsController;
use App\Http\Controllers\API\ProjectsController as ProjectsApiController;
use App\Http\Controllers\TestModerationController;

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
                Route::get('/', [NotificationsController::class, 'index']);
                Route::get('/{id}', [NotificationsController::class, 'show']);
                //Route::delete('/{id}', [NotificationsController::class, 'destroy'])->name('destroy');
            });

        Route::group([
            'prefix' => 'profile'
        ],
            function () {
                Route::get('/verification', [AdminController::class, 'verificationsList']);
                Route::post('/verification/accept/{id}', [AdminController::class, 'acceptVerification']);

                Route::post('/verification/decline{id}', [AdminController::class, 'declineVerification']);
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
                Route::post('/start/{id}', [ProjectsApiController::class, 'start']);
                Route::post('/stop/{id}', [ProjectsApiController::class, 'stop']);
                Route::get('/tags', [ProjectsApiController::class, 'tags']);
                Route::get('/tests/{id?}', [ProjectsApiController::class, 'getTests']);
                Route::get('/', [ProjectsApiController::class, 'index']);
                Route::post('/cover/{type}', [ProjectsApiController::class, 'setImage']);
                Route::get('/{id}', [ProjectsApiController::class, 'show']);
                Route::post('/{id}', [ProjectsApiController::class, 'update']);
                Route::post('/', [ProjectsApiController::class, 'create']);

                Route::delete('/destroy/{id}', [ProjectsApiController::class, 'destroy']);



                Route::post('/image/{field}/{type?}', [ProjectsApiController::class, 'setImage']);
            }
        );

        Route::get('/export-users/{project_id}', [UsersController::class, 'exportUsers'])->name('export-users');
        Route::get('/export-users-article/{project_id}/{content_id}', [UsersController::class, 'exportUsersArticle'])->name('export-users-article');
        Route::get('/export-users-test/{project_id}/{content_id}', [UsersController::class, 'exportUsersTest'])->name('export-users-test');

        Route::group(
            [
                'prefix' => 'blog'
            ],
            function () {

                Route::get('/', [\App\Http\Controllers\BlogController::class, 'index']);
                Route::get('/list', [\App\Http\Controllers\BlogController::class, 'list']);

                Route::post('/update/{id}', [BlogController::class, 'update']);
                Route::post('/', [BlogController::class, 'store']);

                Route::get('/tags', [BlogController::class, 'tags']);

                Route::get('/times', [BlogController::class, 'times']);

                Route::get('/{id}', [\App\Http\Controllers\BlogController::class, 'show']);

                Route::delete('/destroy/{id}', [BlogController::class, 'destroy']);
            }
        );

        Route::group(
            [
                'prefix' => 'tests'
            ],
            function () {
                Route::get ('/',  [TestsController::class, 'index']);
                Route::get('/{id}', [TestsController::class, 'show']);
                Route::get('/accept/{id}', [TestModerationController::class, 'accept']);
                Route::get('/decline/{id}', [TestModerationController::class, 'decline']);
                Route::delete('/{id}', [TestsController::class, 'destroy']);
                Route::post('/', [TestsController::class, 'create']);
                Route::post('/submit', [TestsController::class, 'submit']);
            }
        );

        Route::group(
            [
                'prefix' => 'moderation'
            ],
            function () {
                Route::get('/', [ModerationController::class, 'index']);
                Route::get('/{research_id}', [ModerationController::class, 'test']);
            }
        );

        Route::group(
            [
                'prefix' => 'analytics'
            ],
            function () {
                Route::get('/', [AnalyticsController::class, 'index']);
            }
        );

        Route::group(
            [
                'prefix' => 'users'
            ],
            function () {
                Route::get ('/',  [UsersController::class, 'index']);
                Route::get ('/list',  [UsersController::class, 'list']);
                Route::get ('/passing/{project_id}/{status}',  [UsersController::class, 'passing']);
                Route::get ('/passing-test-all/{content_id}/{status}',  [UsersController::class, 'passingTestAll']);
                Route::get ('/passing-article-all/{content_id}/{status}',  [UsersController::class, 'passingArticleAll']);
                Route::get ('/passing-test/{test_id}/{variant}',  [UsersController::class, 'passingTest']);
                Route::get('/{id}', [UsersController::class, 'show']);
                Route::delete('/destroy/{id}', [UsersController::class, 'destroy']);
                Route::post('/balance', [UsersController::class, 'balance']);
                Route::get('/create-name/{name}', [UsersController::class, 'createName']);

                Route::post('/', [UsersController::class, 'create']);
                Route::post('/{id}', [UsersController::class, 'update']);
                Route::get('/block/{id}', [UsersController::class, 'block']);
                Route::get('/unblock/{id}', [UsersController::class, 'unblock']);
                Route::get('/search/{query}', [UsersController::class, 'search']);
            }
        );
    });
