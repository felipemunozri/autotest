<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'locations';

    protected $fillable = [
        'code',
        'name',
        'state_id',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
