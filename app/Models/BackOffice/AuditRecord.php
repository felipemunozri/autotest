<?php

namespace App\Models\BackOffice;

use App\Models\CustomModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditRecord extends CustomModel
{
    use HasFactory;

    protected $table = 'audit_records';

    protected $fillable = [
        'log_name',
        'description',
        'tenant_id',
        'tenant_name',
        'target_id',
        'target_type',
        'user_id',
        'user_name',
        'user_lastname',
        'user_email',
        'attributes',
        'old',
    ];

    public function causedBy(User $user)
    {
        $this->attributes['user_id'] = $user->id;
        $this->attributes['user_name'] = $user->name ?? null;
        $this->attributes['user_lastname'] = $user->lastname ?? null;
        $this->attributes['user_email'] = $user->email;

        return $this;
    }

    public function target($model)
    {
        $this->attributes['target_id'] = $model->id;
        $this->attributes['target_type'] = get_class($model);

        return $this;
    }

    public function tenant($tenant)
    {
        $this->attributes['tenant_id'] = $tenant->id;
        $this->attributes['tenant_name'] = $tenant->name;

        return $this;
    }

    public function old($old)
    {
        $this->attributes['old'] = json_encode($old);

        return $this;
    }

    public function withProperties($props)
    {
        $this->attributes['attributes'] = json_encode($props);

        return $this;
    }

    public function description($description)
    {
        $this->attributes['description'] = $description;

        return $this;
    }

    public function logName($logName = 'default')
    {
        $this->attributes['log_name'] = $logName;

        return $this;
    }


}
