<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $activationUrl;
    public $userName;

    public function __construct($activationUrl, $userName)
    {
        $this->activationUrl = $activationUrl;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->view('emails.activation')
                    ->subject('Kích hoạt tài khoản BookBee');
    }
}
