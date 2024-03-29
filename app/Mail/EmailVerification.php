<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return EmailVerification
     */
    public function build()
    {
        try {
            return $this->view('emails.mail')->with([
                'email_token' => $this->user->email_token, ]);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }

    }

}// end of class
