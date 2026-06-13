<?php

namespace App\Mail;

use Illuminate\Bus\Queueable; //يسمح بإرسال الإيميلات في الخلفية.
use Illuminate\Mail\Mailable; //الكلاس الأساسي لكل رسائل Laravel.
use Illuminate\Queue\SerializesModels; //يساعد Laravel على تحويل البيانات ونقلها عند استخدام Queue.

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
//لاستقبال كود التحقق وتمريره إلى الرسالة.
    public function __construct($code)
    {
        $this->code = $code;
    }
//بناء الرسالة وتحديد عنوانها ومحتواها.
    public function build()
    {
        return $this->subject('Email Verification Code')
            ->view('emails.verification-code');
    }
}
