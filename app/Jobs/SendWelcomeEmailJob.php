<?php

namespace App\Jobs;

use App\Mail\Welcome\VehicleActivated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $plate_number;
    private $name;
    private $tenant;

    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //$email = new SendWelcomeBeneficiaryMail();
        Mail::to($this->email)->send(new VehicleActivated($this->email, $this->plate_number, $this->name, $this->tenant));
    }
}
