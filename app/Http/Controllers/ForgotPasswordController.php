<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{

    public function forgot(Request $request) {

        $request->validate([
            'email' => 'required|email'
        ]);
        error_log('heloooooo -> ');

        $user = User::where('email',  $request['email'])->first();
        if(!$user) {
            return response()->json([
               'message' => 'NoEmailFound',
               'status_code' => 404
            ], 404);
        } else {
            $random = base64_encode($request['email']);
            $user->verification_code = $random;
            if ($user->save()) {
                $userData = array(
                    'email' => $user->email,
                    'name' => $user->name,
                    'random' => $random
                );
//                Mail::send( 'emails.reset_password', $userData, function ($message) use ($userData) {
//                    $message->from('info@email.com', 'User Name');
//                    $message->to($userData['email'], $userData['full_name']);
//                });
                $to_email = $request['email'];

                if (!empty($request['locale'])) {
                    if ($request['locale'] == 'en') {
                        $locale = 'emails.reset_password_en';
                        $subject = 'Reset Password';
                    } else if($request['locale'] == 'de') {
                        $locale = 'emails.reset_password_de';
                        $subject = 'Passwort zurücksetzen ';
                    } else if($request['locale'] == 'al') {
                        $locale = 'emails.reset_password_al';
                        $subject = 'Ndrro Fjalkalimin';
                    }
                } else {
                    $locale = 'emails.reset_password_en';
                    $subject = 'Reset Password';
                }

                Mail::send($locale, $userData, function ($message) use ($to_email, $subject) {
                    $message->from("info@emrabebe.com", 'EmraBebe.com');
                    $message->to($to_email)->subject($subject);
                });

                if (Mail::failures()) {
                    return response()->json([
                        'message' => 'Mail Server not working please try again latter',
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
                    'message' => 'Cannot save user for forget password',
                    'status_code' => 400
                ], 400);
            }
        }
    }

    public function resetPassword(Request $request) {

        $pw = $request['password'];
        $token = $request['token'];

        if($pw && $token) {
            $user = User::where('verification_code', $token)->first();
            if (!empty($user)) {
                $user->password = bcrypt($pw);
                if($user->save()){
                    return redirect(env('EMRA_BEBE_LOGIN'));
                }
            } else {
                return response()->json([
                    'message' => 'Cannot find user associated to this credentials',
                    'status_code' => 400
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Cannot save new password',
                'status_code' => 400
            ], 400);
        }
    }

}
