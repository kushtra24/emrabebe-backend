<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;

class SendVerificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $locale;

    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $locale
     */
    public function __construct($user, $locale)
    {
        $this->user = $user;
        $this->locale = $locale;
    }

    /**
     * Execute the job.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle()
    {
        try{
           $email = new EmailVerification($this->user);

           Mail::to($this->user->email)->send($email);
            if (!empty($this->locale)) {
                if ($this->locale == 'en') {
                $locale = 'emails.register_email_en';
                    $subject = 'Verify email';
                } else if($this->locale == 'de') {
                $locale = 'emails.register_email_de';
                    $subject = 'EMail bestätigen';
                } else if($this->locale == 'al') {
                $locale = 'emails.register_email_al';
                    $subject = 'Verifiko Emailin';
                }
            } else {
                $locale = 'emails.register_email_en';
                $subject = 'Verify email';
            }

            $to_email = $this->user->email;
            $data = array('name' => $this->user->name, 'email_token' => $this->user->verification_code);
                Mail::send($locale, $data, function ($message) use ($to_email, $subject) {
                    $message->from("noreplay@emrabebe.com", 'emrabebe.com');
                    $message->to($to_email)->subject($subject);
                    return response()->json('email send', 201);
                });

            if (Mail::failures()) {
                return response()->json([
                    'message' => 'Mail Server not working please try again latter',
                    'status_code' => 500
                ], 500);
            }
        }
        catch (\Exception $e){
            return response()->json($e, 400);
        }
    }
}
