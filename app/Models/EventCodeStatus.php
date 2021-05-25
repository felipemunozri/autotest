<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCodeStatus extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'event_code_status';

    protected $fillable = [
        'name',
        'code'
    ];
}
