<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// register
Route::post('register', function(Request $request) {
    return response()->json($request, 201);
});

//login
Route::post('/login', AuthController::class);

Route::post('/forgot', [ForgotPasswordController::class, 'forgot']);
Route::post('/reset', [ResetPasswordController::class, 'reset']);

// log out
Route::post('logout', [AuthController::class, 'logout']);

//Route::get('articles', 'ArticleController@index');
//Route::post('articles', 'ArticleController@store');
//Route::put('articles/{id}', 'ArticleController@update');
//Route::delete('articles/{id}', 'ArticleController@destroy');


//Route::get('articles', [ArticleController::class, 'index']);
//Route::get('article/{slug}', [ArticleController::class, 'show']);
//Route::put('articles/{id}', [ArticleController::class, 'update']);
