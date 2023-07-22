<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

// Route::get('/sendemail', function () {
// //    return view('welcome');

//    $to_name = 'kushtrim';
//    $to_email = 'kushtra24@gmail.com';
//    $data = array("name"=>"kushtrimoo", "body"=>"test email");
//    Mail::send('emails.mail', $data, function ($message) use ($to_email) {
//        $message->from("kushtrimcoding@gmail.com", 'username');
//        $message->to($to_email)
//        ->subject('laravel main subject');
//    });

// });

Route::get('/', function () {
    return view('welcome');
});

Route::get('/verifyemail/{token}', [AuthController::class, 'verify']);

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');
