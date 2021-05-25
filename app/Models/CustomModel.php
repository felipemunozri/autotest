<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomModel extends Model
{
    use HasFactory, AutoGenerateUuid;

    protected $casts = [
        'id' => 'string'
    ];

    protected $keyType = 'string';
}
