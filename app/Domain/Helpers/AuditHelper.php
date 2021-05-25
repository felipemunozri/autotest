<?php


namespace App\Domain\Helpers;


use App\Models\BackOffice\AuditRecord;

class AuditHelper
{
    public function storeAuditRecord($payload, $action, $old = null)
    {
        $user = auth()->user();
        $tenant = UserHelper::myTenant();

        $record = new AuditRecord();
        $record->target($payload)
            ->tenant($tenant)
            ->causedBy($user)
            ->withProperties($payload)
            ->old($old)
            ->description($action);

        $record->save();
    }
}

