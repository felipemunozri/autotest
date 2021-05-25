<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionType extends CustomModel
{
    use HasFactory, AutoGenerateUuid;

    protected $connection = 'public';

    protected $table = 'action_types';

    protected $fillable = [
        'name',
        'description',
    ];

    public $timestamps = false;
}
