<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetabaseReport extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'metabase_reports';

    protected $fillable = [
        'tenant_id',
        'code',
        'metabase_report_id'
    ];

    public $timestamps = false;

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}