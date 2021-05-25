<?php

namespace App\Models;

use App\Models\Acl\Role;
use Illuminate\Broadcasting\PrivateChannel;
use App\Traits\AutoGenerateUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, AutoGenerateUuid, HasRoles;

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dni',
        'name',
        'second_name',
        'lastname',
        'second_lastname',
        'email',
        'phone',
        'password',
        'provider',
        'provider_id',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'email_verified_at' => 'datetime',
    ];

    public function setDniAttribute($value)
    {
        $this->attributes['dni'] = strtoupper($value);
    }

    public function profiles()
    {
        return $this->belongsToMany(Role::class, 'acl.model_has_roles', 'model_id', 'role_id')
            ->withPivot('tenant_id', 'model_type');
    }

    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'tenant_user', 'user_id', 'tenant_id')
            ->withPivot('user_status_id', 'disabled_web', 'disabled_mobile');
    }

    public function currentTenant()
    {
        $userPreferences = \DB::table('user_preferences')->where('user_id', $this->id)->first();
        $tenant = Tenant::query()->where('id', $userPreferences->current_tenant_id)->firstOrFail();

        return $tenant;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

}
