<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantUser extends Model
{
    use HasFactory;

    protected $table = 'tenant_user';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'disabled_web',
        'disabled_mobile',
        'user_status_id',
    ];

    protected $primaryKey = ['user_id', 'stock_id'];
    public $incrementing = false;

    protected $casts = [
        'tenant_id' => 'string',
        'user_id' => 'string'
    ];
}
