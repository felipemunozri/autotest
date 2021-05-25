<?php

namespace App\Providers;

use App\Models\Event;
use Illuminate\Support\ServiceProvider;
use App\Models\Acl\Permission;
use App\Models\Acl\Role;
use Ramsey\Uuid\Uuid;

class AppServiceProvider extends ServiceProvider
{
    public const SECURE_ENVIRONMENTS = ['local', 'develop', 'production'];
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(\Illuminate\Http\Request $request)
    {
        Permission::retrieved(function (Permission $permission) {
            $permission->incrementing = false;
        });

        Permission::creating(function (Permission $permission) {
            $permission->incrementing = false;
            $permission->id = Uuid::uuid4()->toString();
        });

        Role::retrieved(function (Role $role) {
            $role->incrementing = false;
        });

        Role::creating(function (Role $role) {
            $role->incrementing = false;
            $role->id = Uuid::uuid4()->toString();
        });

        if (\in_array(env('APP_ENV', null), self::SECURE_ENVIRONMENTS) ) {
            if ($request->server->has('HTTP_X_ORIGINAL_HOST')) {
                $this->app['url']->forceRootUrl($request->server->get('HTTP_X_FORWARDED_PROTO').'://'.$request->server->get('HTTP_X_ORIGINAL_HOST'));
                $this->app['url']->forceScheme('https');
            }
        }
    }
}
