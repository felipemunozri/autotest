<?php

namespace App\Listeners;

use App\Domain\Helpers\MailHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        (new MailHelper())->welcomeEmail($event->email, $event->plateNumber, $event->name, $event->tenant ?? null);
    }
}
