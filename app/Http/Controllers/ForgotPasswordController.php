<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{

    public function forgot(Request $request) {

        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email',  $request->email)->first();
        if(!$user) {
            return response()->json([
               'message' => 'we have sent a verification code to your email address',
               'status_code' => 200
            ], 200);
        } else {
            $random = rand(11111, 99999);
            $user->verification_code = $random;
            if ($user->save()) {
                $userData = array(
                    'email' => $user->email,
                    'full_name' => $user->name,
                    'random' => $random
                );
                Mail::send( 'emails.reset_password', $userData, function ($message) use ($userData) {
                    $message->from('info@email.com', 'User Name');
                    $message->to($userData['email'], $userData['full_name']);
                });
                if (Mail::failures()) {
                    return response()->json([
                        'message' => 'Some error occurred, Please try again',
                        'status_code' => 500
                    ], 500);
                } else {
                    return response()->json([
                        'message' => 'We have send an email',
                        'status_code' => 200
                    ], 200);
                }
            } else {
                return response()->json([
                    'message' => 'Some error occurred, Please try again',
                    'status_code' => 500
                ], 500);
            }
        }
    }

}
