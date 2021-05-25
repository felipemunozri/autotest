<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'currencies';

    protected $fillable = [
        'name',
        'code',
        'referency_dollar',
    ];
}
