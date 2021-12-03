<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\API\ProjectsController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\NotificationsController;
use App\Http\Controllers\API\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Components\Filter;
use App\Http\Components\AccountsWithdrawFilter;
use App\Http\Controllers\API\UsersController;
use App\Http\Controllers\API\ModerationController;
use App\Http\Controllers\API\WithdrawController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AnalyticsController;
use App\Http\Controllers\API\TestsController;
use App\Http\Controllers\API\BannersController;


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

/**/

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class,'registration']);
//    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});
Route::group(
    [
        'middleware' => 'auth:api',

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

        Route::group(
            [
                'prefix' => 'users'
            ],
            function () {
                Route::get ('/',  [UsersController::class, 'index'])->middleware('auth:api');
                Route::get ('/list',  [UsersController::class, 'list']);
                Route::get ('/passing/{project_id}/{status}',  [UsersController::class, 'passing']);
                //Route::get('/{id}', [UsersController::class, 'show']);
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

        Route::group(
            [
                'prefix' => 'project'
            ],
            function () {
                Route::get('/', [ProjectsController::class, 'index']);
                Route::get('/{id}', [ProjectsController::class, 'show']);

//                Route::post('/start', [ProjectsController::class, 'start']);
//                Route::post('/stop/{id}', [ProjectsController::class, 'stop']);
//                Route::get('/tags', [ProjectsController::class, 'tags']);
//                Route::get('/tests/{id?}', [ProjectsController::class, 'getTests']);
//                Route::post('/cover/{type}', [ProjectsController::class, 'setImage']);
//                Route::post('/image/{type}', [ProfileController::class, 'setImage']);
//                Route::post('/{id}', [ProjectsController::class, 'update']);
//                Route::post('/', [ProjectsController::class, 'create']);
            }
        );

        Route::group(
            [
                'prefix' => 'blog'
            ],
            function () {

                Route::get('/', [BlogController::class, 'index']);
//                Route::get('/list', [BlogController::class, 'list']);

                Route::post('/', [BlogController::class, 'store']);
                Route::post('/update', [BlogController::class, 'update']);

                Route::get('/tags', [BlogController::class, 'tags']);

                Route::get('/times', [BlogController::class, 'times']);

                Route::get('/{id}', [BlogController::class, 'show']);

                Route::get('/{id}/callback', [BlogController::class, 'callback']);

                Route::get('/{articleId}/read/{blockId}', [BlogController::class, 'read']);

                Route::post('/filter/export',
                    [Filter::class, 'onExport']);

                Route::delete('/destroy/{id}', [BlogController::class, 'destroy']);
            }
        );

        Route::group(
            [
                'prefix' => 'notifications'
            ],
            function () {
                Route::get('/', [NotificationsController::class, 'index']);
                Route::get('/{id}', [NotificationsController::class, 'show']);
                Route::delete('/{id}', [NotificationsController::class, 'destroy'])->name('destroy');

            }
        );

        Route::group(
            [
                'prefix' => 'profile'
            ],
            function () {
                Route::get('/',
                    [ProfileController::class, 'index']);

                Route::post('/password',
                    [ProfileController::class, 'setPassword']);

                Route::post('/token',
                    [ProfileController::class, 'token']);

                Route::post('/withdraw',
                    [ProfileController::class, 'withdraw'])
                    ;

                Route::post('/verify',
                    [ProfileController::class, 'verify']);

                Route::post('/image/{type}',
                    [ProfileController::class, 'setImage']);

                Route::get('/decline?id={id}',
                    [ProfileController::class, 'decline']);

                Route::get('/confirm?id={id}',
                    [ProfileController::class, 'confirm']);
                    //->middleware('\Tymon\JWTAuth\Http\Middleware\Check'/*, 'ULogic\Profile\Models\BanMiddleware'*/);

                Route::get('/confirm-request/{id}', [ProfileController::class, 'confirmRequest']);
                Route::get('/decline-request/{id}', [ProfileController::class, 'declineRequest']);

                Route::get('/accept/{id}', [ProfileController::class, 'onAcceptVerification']);
                Route::get('/decline/{id}', [ProfileController::class, 'onDeclineVerification']);
            }
        );

        Route::group(
            [
                'prefix' => 'withdraw'
            ],
            function () {
                Route::resource('/',
                    WithdrawController::class,
                    ['except' => ['store', 'destroy', 'create', 'edit', 'show', 'update']])
                    ;
            }
        );

        Route::group(
            [
                'prefix' => 'tests'
            ],
            function () {
                Route::get ('/',  [TestsController::class, 'index']);
                Route::get('/{id}', [TestsController::class, 'show']);
                Route::delete('/{id}', [TestsController::class, 'destroy']);
                Route::post('/', [TestsController::class, 'create']);
                Route::post('/submit', [TestsController::class, 'submit']);
                //Route::post('/{id}', [TestsController::class, 'update']);
            }
        );

        Route::group(
            [
                'prefix' => 'account'
            ],
            function () {
                Route::post('/filter/export',
                    [Filter::class, 'onExport']);
            }
        );

        Route::group(
            [
                'prefix' => 'filter'
            ],
            function () {
                Route::post('/export',
                    [Filter::class, 'onExport']);
            }
        );

        Route::group(
            [
                'prefix' => 'moderation'
            ],
            function () {
                Route::get('/', [ModerationController::class, 'index']);
                Route::get('/{test_id}', [ModerationController::class, 'test']);
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
                'prefix' => 'agreement'
            ],
            function () {
                Route::get('/{id}', [TestsController::class, 'showAgreement']);
                Route::post('/{id}', [TestsController::class, 'acceptAgreement']);

            }
        );


       /**  Запросы от внешних источников */
        Route::group(
            [
                'prefix' => 'cards'
            ],
            function () {
                Route::get('/{id?}', [CardsController::class, 'index']);
                Route::get('/buy/{id}', [CardsController::class, 'buy']);
            }
        );

        Route::group(
            [
                'prefix' => 'banners'
            ],
            function () {
                Route::get('/{type}', [BannersController::class, 'show']);
            }
        );

        Route::group(
            [
                'prefix' => 'users'
            ],
            function () {
                Route::get('/cards/{user_id}', [UsersController::class, 'cards']);
            }
        );

    }
);
