<?php

namespace App\Providers;

use App\Events\ActionPerformed;
use App\Events\DeviceActivated;
use App\Events\SmsSent;
use App\Listeners\SendWelcomeEmail;
use App\Listeners\StoreAudit;
use App\Listeners\UpdateSimCardBalance;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

#events
use App\Events\TaskAnswer\RegisteredAnswer;
use App\Events\Task\RegisteredTask;

#listeners

use App\Listeners\Event\TriggerNewAnswerBroadcast;
use App\Listeners\FakerSmsClient\CreateFakeAnswer;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        RegisteredAnswer::class => [
            TriggerNewAnswerBroadcast::class,
        ],
        RegisteredTask::class => [
            CreateFakeAnswer::class,
        ],
        SmsSent::class => [
            UpdateSimCardBalance::class,
        ],
        DeviceActivated::class => [
            SendWelcomeEmail::class,
        ],
        ActionPerformed::class => [
            StoreAudit::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
