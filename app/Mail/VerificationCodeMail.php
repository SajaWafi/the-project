<?php

namespace App\Mail;

use Illuminate\Bus\Queueable; 
use Illuminate\Mail\Mailable; 
use Illuminate\Queue\SerializesModels; 

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
//receive code
    public function __construct($code)
    {
        $this->code = $code;
    }
//build email and subject
    public function build()
    {
        return $this->subject('Email Verification Code')
            ->view('emails.verification-code');
    }
}
