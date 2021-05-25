<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'countries';

    protected $fillable = [
        'name',
        'code',
        'code_number',
        'utc',
    ];

    public $timestamps = false;

    public function states()
    {
        return $this->hasMany(State::class, 'country_id');
    }
}
