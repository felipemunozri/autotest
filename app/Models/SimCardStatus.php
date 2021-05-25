<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimCardStatus extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'sim_card_status';

    protected $fillable = [
        'name',
        'description',
    ];

    public $timestamps = false;
}
