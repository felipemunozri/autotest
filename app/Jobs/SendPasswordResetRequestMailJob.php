<?php

namespace App\Jobs;

use App\Mail\PasswordResetRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPasswordResetRequestMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $token;
    private $url;
    private $name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $token, $url, $name = null)
    {
        $this->token = $token;
        $this->email = $email;
        $this->url = $url;
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new PasswordResetRequest($this->email, $this->token, $this->url, $this->name));
    }
}
