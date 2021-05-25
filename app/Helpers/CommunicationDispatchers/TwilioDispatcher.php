<?php

namespace App\Helpers\CommunicationDispatchers;

use Illuminate\Support\Facades\Config;
use Twilio\Rest\Client;

class TwilioDispatcher implements DispatcherInterface
{
    private $sid;
    private $token;
    private $from;
    private $client;

    public function __construct(string $sid = null, string $token = null, string $from = null) {
      $this->sid = $sid ? $sid : Config::get('sms-operator.sid');
      $this->token = $token ? $token : Config::get('sms-operator.token');
      $this->from = $from ? $from : Config::get('sms-operator.phone_number');
      $this->client = new Client($this->sid, $this->token);
    }

    public function sendSMS($phone_number, $message)
    {
      try {
        return $this->client->messages->create(
          $phone_number,
          [
            'from' => $this->from,
            'body' => $message
          ]
        );
      } catch (Exception $e) {
        \Log::error($e);
      }
      
    }

    public function callPhone($phone_number)
    {
      try {
        return $this->calls->create(
          $phone_number,
          $this->from,
          [
            "twiml" => "<Response><Say>Ahoy there!</Say></Response>"
          ]
        );
      } catch (Exception $e) {
        \Log::error($e);
      }
      
    }
}
