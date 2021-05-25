<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'actions';

    protected $fillable = [
        'name',
        'description',
        'tenant_id',
        'action_type_id',
        'detail',
        'enabled',
        'code'
    ];

    public $timestamps = false;

    public function getDetailFixAttribute()
    {
        return \json_decode($this->attributes['detail']);
    }
}
