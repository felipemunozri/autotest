<?php

namespace App\Models\Acl;

//use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoGenerateUuid;
use Spatie\Permission\Models\Role as DefaultRole;

class Role extends DefaultRole
{
    use AutoGenerateUuid;

    protected $table = 'acl.roles';

    protected $casts = [
        'id' => 'string'
    ];

    protected $keyType = "string";

    public $incrementing = false;
}
