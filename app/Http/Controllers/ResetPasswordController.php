<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class resetPasswordController extends Controller
{
        public function reset(Request $request) {

            $request->validate([
                'email' => 'required|email',
                'verification_code' => 'required',
                'password' => 'required|confirmed|min:6'
            ]);
            $user = User::where('email', $request->email)->where('verification_code', $request->verification_code)->first();
            if(!$user) {
                return response()->json([
                   'message' => 'User not found/invalid code',
                   'status_code' => 401
                ], 401);
            } else {
                $user->password = bcrypt(trim($request->password));
                $user->verification_code = null;
                if ($user->save()) {
                   return response()->json([
                      'message' => 'password changed successfully',
                      'status_code' => 200
                  ], 200);
                } else {
                    return response()->json([
                        'message' => 'Some error occurred, Please try again',
                        'status_code' => 500
                    ], 500);
                }
            }

            return $user;
        }

}
