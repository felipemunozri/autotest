<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends CustomModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tenants';

    protected $fillable = [
        'code',
        'dni',
        'name',
        'address',
        'contact',
        'country_id',
        'location_id',
        'location',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_user')
            ->withPivot(['disabled_web', 'disabled_mobile', 'user_status_id']);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function metabaseReports() {
        return $this->hasMany(MetabaseReport::class, 'tenant_id');
    }

    public function getLocationAttribute()
    {
        return json_decode($this->attributes['location']);
    }
}
