<?php

namespace App\Http\Controllers\Api\V1\Mail;

use App\Domain\Helpers\MailHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\Tenant;
use Illuminate\Http\Request;

class EmailWelcomeController extends BaseApiController
{
    public function store(Request $request)
    {
        $tenantId = $request->input('tenant_id');
        $beneficiaryId = $request->input('beneficiary_id');

        $plateNumber = $request->input('plate_number');
        $email = $request->input('email');
        $tenant = Tenant::query()->where('id', $tenantId)->firstOrFail();
        $receiver = Beneficiary::query()->where('id', $beneficiaryId)->firstOrFail();

        $receiverName = $receiver ? $receiver->name.' '.$receiver->lastname : null;
        $tenantName = $tenant ? $tenant->name : null;

        (new MailHelper())->welcomeEmail($email, $plateNumber, $receiverName ?? null, $tenantName ?? null);

        return response()->json(['message' => 'Email sent']);
    }
}
