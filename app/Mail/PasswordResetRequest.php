<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Solicitud de reinicio de contraseña';

    private $email;
    private $token;
    private $url;
    private $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $token, $url, $name = 'Usuario')
    {
        $this->token = $token;
        $this->email = $email;
        $this->url = $url;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = $this->url.'?token='.$this->token.'&email='.$this->email;

        return $this->subject($this->subject)
            ->markdown('emails.password.password_reset')
            ->with([
                'user' => $this->name,
                'email' => $this->email,
                'url' => $url,
                'token' => $this->token,
            ]);
    }
}
