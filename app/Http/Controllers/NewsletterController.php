<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NewsletterController extends Controller
{

    private $newsletterRules = [
        'email' => 'required|email'
    ];

    public function store(Request $request) {
        // is it an email?
        try {
            $this->validate($request, $this->newsletterRules);
        } catch (ValidationException $e) {
            throw new \InvalidArgumentException('notValid', 422);
        }
        // is the email already in the database?
        $email = Newsletter::where('email', $request['email'])->first();
        if ($email) {
            throw new \InvalidArgumentException('EmailAlreadyExists', 403);
        }
        // save email in database
        $email = $request['email'];
        Newsletter::create($request->all());
        return response()->json($email, 200);
    }

    public function destroy(Request $request) {
        $email = Newsletter::where('email', $request['email'])->first();
        $email->delete();
        return response()->json('Deleted', 200);
    }

}
