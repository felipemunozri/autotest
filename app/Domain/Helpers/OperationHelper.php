<?php

namespace App\Domain\Helpers;

use App\Helpers\CommunicationDispatchers\TwilioDispatcher;
use App\Helpers\CommunicationDispatchers\FakerSmsClient;
use App\Models\Task;
use App\Models\EventCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class OperationHelper
{
    private $service;
    private $client;

    public function __construct()
    {
        $this->service = Config::get('sms-operator.service');
        switch ($this->service) {
            case 'twilio':
                $this->client = new TwilioDispatcher();
                break;
            default:
                $this->client = new FakerSmsClient();
                break;
        }
    }

    public function sendSMS($phoneNumber, $message)
    {
        try {
            return $this->client->sendSMS($phoneNumber, $message);
        } catch (Exception $e) {
            \Log::error($e);
        }
    }

    public function storeTask($params)
    {
        $task = new Task();
        $task->fill($params);
        $task->save();

        (new AuditHelper())->storeAuditRecord($task, 'Task created');

        return $task;
    }
}
