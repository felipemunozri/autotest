<?php

namespace App\Mail\Welcome;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VehicleActivated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject = 'Bienvenido a AutoSeguro365';

    private $email;
    private $plate_number;
    private $name;
    private $tenant;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $plate_number, $name, $tenant = null)
    {
        $this->email = $email;
        $this->plate_number = $plate_number;
        $this->name = $name;
        $this->tenant = $tenant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->markdown('emails.welcome.vehicle_activated')
                    ->with([
                        'beneficiary' => $this->name,
                        'plate_number' => $this->plate_number,
                        'email' => $this->email,
                        'tenant' => $this->tenant
                    ]);
    }
}
