<?php

namespace App\Models\Acl;

//use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoGenerateUuid;
use Spatie\Permission\Models\Permission as DefaultPermission;

class Permission extends DefaultPermission
{
    use AutoGenerateUuid;

    protected $table = 'acl.permissions';

    protected $casts = [
        'id' => 'string'
    ];

    protected $keyType = "string";

    public $incrementing = false;
}
