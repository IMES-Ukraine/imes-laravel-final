<?php

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

        Route::group(
            [
                'prefix' => 'users'
            ],
            function () {
                Route::resource('/',
                    UsersController::class,
                    ['except' => ['index', 'destroy', 'create', 'edit']]
                );
            }
        );

        Route::group(
            [
                'prefix' => 'project'
            ],
            function () {
                Route::get('/', [ProjectsController::class, 'index']);
                Route::get('/{id}', [ProjectsController::class, 'show']);
                Route::post('/{id}', [ProjectsController::class, 'update']);
                Route::post('/', [ProjectsController::class, 'create']);

                Route::post('/image/{type}', [ProfileController::class, 'setImage']);
            }
        );

        Route::group(
            [
                'prefix' => 'blog'
            ],
            function () {

                Route::get('/', [BlogController::class, 'index'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check');

                Route::get('/{id}', [BlogController::class, 'show'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check', 'App\Models\BanMiddleware');

                Route::get('/{id}/callback', [BlogController::class, 'callback'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check', 'App\Models\BanMiddleware');

                Route::get('/{articleId}/read/{blockId}', [BlogController::class, 'read'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check', 'App\Models\BanMiddleware');

                Route::post('/filter/export',
                    [Filter::class, 'onExport'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check', 'App\Models\BanMiddleware');
            }
        );

        Route::group(
            [
                'prefix' => 'notifications'
            ],
            function () {
                Route::get('/', [NotificationsController::class, 'index'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check', 'App\Models\BanMiddleware');
                Route::get('/{id}', [NotificationsController::class, 'show'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check', 'App\Models\BanMiddleware');
                Route::delete('/{id}', [NotificationsController::class, 'destroy'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check', 'App\Models\BanMiddleware')->name('destroy');

            }
        );

        Route::group(
            [
                'prefix' => 'profile'
            ],
            function () {
                Route::get('/',
                    [ProfileController::class, 'index'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check', 'App\Models\BanMiddleware');

                Route::post('/password',
                    [ProfileController::class, 'setPassword'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check', 'App\Models\BanMiddleware');

                Route::post('/token',
                    [ProfileController::class, 'token'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check', 'App\Models\BanMiddleware');

                Route::get('/withdraw',
                    [ProfileController::class, 'index'])
                    ->middleware('\Tymon\JWTAuth\Http\Middleware\Check', 'App\Models\BanMiddleware');

                Route::post('/verify',
                    [ProfileController::class, 'verify'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check', 'App\Models\BanMiddleware');

                Route::post('/image/{type}',
                    [ProfileController::class, 'setImage']);

                Route::get('/decline',
                    [ProfileController::class, 'decline'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check'/*, 'ULogic\Profile\Models\BanMiddleware'*/);

                Route::get('/confirm',
                    [ProfileController::class, 'confirm'])
                    ->middleware('\Tymon\JWTAuth\Http\Middleware\Check'/*, 'ULogic\Profile\Models\BanMiddleware'*/);

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
                    ->middleware('\Tymon\JWTAuth\Http\Middleware\Check');
            }
        );

        Route::group(
            [
                'prefix' => 'account'
            ],
            function () {
                Route::post('/filter/export',
                    [Filter::class, 'onExport'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check');
            }
        );

        Route::group(
            [
                'prefix' => 'filter'
            ],
            function () {
                Route::post('/export',
                    [Filter::class, 'onExport'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check', 'App\Models\BanMiddleware');
            }
        );

        Route::group(
            [
                'prefix' => 'moderation'
            ],
            function () {
                Route::get('/',
                    [ModerationController::class, 'index']);
            }
        );

        Route::group(
            [
                'prefix' => 'analytics'
            ],
            function () {
                Route::get('/', [AnalyticsController::class, 'index'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check');
            }
        );

    }
);
