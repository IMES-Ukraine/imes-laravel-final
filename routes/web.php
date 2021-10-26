<?php

use App\Models\Article;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\UsersController;

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

Route::get('/share/{id}', function ($id) {

    $model = Article::find($id);
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
