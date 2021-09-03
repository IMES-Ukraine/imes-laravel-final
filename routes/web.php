<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('admin/api/v1/notification/all',
    [AdminController::class, 'NotificationSendAll']
);

Route::post('admin/api/v1/notification/to',
    [AdminController::class, 'notificationSendTo']
);

Route::get('admin/api/v1/withdraw',
    [ProfileController::class, 'withdraws'])->middleware('\Tymon\JWTAuth\Http\Middleware\Check'/*, 'ULogic\Profile\Models\BanMiddleware'*/);


Route::get('/share/{id}', function ($id) {

    $model = \App\Models\Article::find($id);
    $content = json_decode($model->content);

    return view('share',[
        'article' => $model,
        'content' => $content
    ]);
})->where('id', '.*');
/**
 * Redirect all request to index
 */
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
