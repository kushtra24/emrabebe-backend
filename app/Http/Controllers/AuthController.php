<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{

    public function __invoke(Request $request) {


        if(!auth()->attempt($request->only('email', 'password'))) {
            throw new AuthenticationException();
        }

        $user = User::where('email',  $request->email)->first();
        if($user->email_verified_at == null) {
            return response()->json([
                'message' => 'Not verified',
                'status_code' => 403
            ], 403);
        }

        $request->session()->regenerate();

        return response()->json(null, 201);
    }

    public function register(Request $request) {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        if ($request->password != $request->retypePassword) {
            return response()->json([
                'message' => 'password confirmation is wrong',
                'status_code' => 422
            ], 422);
        }

        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = bcrypt($request['password']);
        $user->role = 'user';
        $user->save();

        event(new Registered($user));

        return response()->json(null, 200);
    }

    public function logout(Request $request) {
        auth()->guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json(null, 200);
    }
}
