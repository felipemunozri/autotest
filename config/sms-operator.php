<?php

return [
    'service' => env('SERVICE_SMS', 'fakerClient'),
    'sid' => env('TWILIO_SID', 'ACbef195bf21b9fccb0e82cf68cb59cd52'),
    'token' => env('TWILLIO_TOKEN', 'e79ed6f699d59bb1df307cb8f7f7de30'),
    'phone_number' => env('TWILIO_PHONE_NUMBER', '+56227125422'),
];
