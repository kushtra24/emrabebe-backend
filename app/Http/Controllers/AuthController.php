<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendVerificationEmail;

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

        return response()->json('Authenticated success', 201);
    }

    public function register(Request $request) {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($request['password'] != $request['retypePassword']) {
            return response()->json([
                'message' => 'password confirmation is wrong',
                'status_code' => 422
            ], 422);
        }

        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = $request['password']; // bcrypt($request['password']);
        $user->verification_code = base64_encode($request['email']);
        $user->locale = $request['locale'];
        $user->role = 'user';
        dispatch(new SendVerificationEmail($user, $user->locale));

        if (Mail::failures()) {
            return response()->json([
                'message' => 'Mail Server not working please try again latter',
                'status_code' => 500
            ], 500);
        }

        $user->save();

        event(new Registered($user));

        return response()->json('Registerd!!', 200);
    }

    public function logout(Request $request) {
        auth()->guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json('Loged out', 200);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verify(Request $request, $token)
    {
        $user = User::where('verification_code', $token)->first();

        if (!empty($user)) {
            $user->email_verified_at = now();
            if($user->save()){
                $request->session()->regenerate();
                $redirectHere = env('EMRA_BEBE_LOGIN');
                if($user->locale == 'en') {
                    $user->locale = '';
                }
                return redirect($redirectHere . $user->locale .'/auth/Login');
            }
        }
    }
}
