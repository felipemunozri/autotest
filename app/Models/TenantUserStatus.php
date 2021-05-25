<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantUserStatus extends CustomModel
{
    use HasFactory;

    protected $table = 'tenant_user_status';

    protected $fillable = [
        'name',
        'description',
    ];

    public $incrementing = false;
    public $timestamps = false;

}
