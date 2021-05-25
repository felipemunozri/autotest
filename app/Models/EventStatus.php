<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventStatus extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'event_status';

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public $timestamps = false;
}
