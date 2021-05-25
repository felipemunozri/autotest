<?php

namespace App\Helpers\CommunicationDispatchers;


interface DispatcherInterface
{
    public function sendSMS($phone_number, $message);
    public function callPhone($phone_number);
}