<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventResult extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'event_results';

    protected $fillable = [
        'name',
        'description',
        'order',
        'visible'
    ];
}
