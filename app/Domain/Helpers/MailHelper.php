<?php


namespace App\Domain\Helpers;

use App\Jobs\SendPasswordResetRequestMailJob;
use App\Jobs\SendWelcomeEmailJob;
use App\Mail\Welcome\VehicleActivated;
use App\Models\Device;
use App\Models\DeviceStatus;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class MailHelper
{
    public function welcomeEmail($email, $plateNumber, $name, $tenant = null )
    {
        /* $payload = (object)[
            'name' => $name,
            'plate_number' => $plateNumber,
            'tenant' => $tenant,
            'email' => $email,
        ]; */

        //Mail::to($email)->send(new VehicleActivated($email, $plateNumber, $name, $tenant));
        dispatch(new SendWelcomeEmailJob($email, $plateNumber, $name, $tenant));
    }

    public function forgotPasswordEmail($email, $token, $url, $name = null)
    {
        dispatch(new SendPasswordResetRequestMailJob($email, $token, $url, $name));
    }


}
