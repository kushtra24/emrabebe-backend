<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BabyNamesController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\UserController;
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

//Auth
Route::post('/login', AuthController::class);
Route::post('register', [AuthController::class, 'register']);
Route::post('/forgot', [ForgotPasswordController::class, 'forgot']);
Route::post('/reset', [MessagesController::class, 'reset']);
Route::post('logout', [AuthController::class, 'logout']);

// Users
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'delete']);

    Route::get('/baby-names', [BabyNamesController::class, 'index']);
    Route::post('/baby-names', [BabyNamesController::class, 'store']);
    Route::get('/baby-names/{id}', [BabyNamesController::class, 'show']);
    Route::put('/baby-names/{id}', [BabyNamesController::class, 'update']);
    Route::delete('/baby-names/{id}', [BabyNamesController::class, 'delete']);

    Route::get('/articles', [ArticleController::class, 'index']);
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);
    Route::put('/articles/{id}', [ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [ArticleController::class, 'delete']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'delete']);

    Route::get('/messages', [MessagesController::class, 'index']);
    Route::post('/messages', [MessagesController::class, 'store']);
    Route::get('/messages/{id}', [MessagesController::class, 'show']);
    Route::put('/messages/{id}', [MessagesController::class, 'update']);
    Route::delete('/messages/{id}', [MessagesController::class, 'delete']);

});
