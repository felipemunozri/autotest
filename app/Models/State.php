<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'states';

    protected $fillable = [
        'code',
        'name',
        'country_id',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'state_id');
    }
}
