<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
//            Route::prefix('api')
//                ->middleware('api')
//                ->namespace($this->namespace)
//                ->group(base_path('routes/api.php'));

            $this->mapPrintRoutes();

            $this->mapApiRoutesV1();

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    protected function mapApiRoutesV1()
    {
//        Route::middleware(['api'])
//            ->namespace("$this->namespace\Api\V1")
//            ->prefix('api/v1')
//            ->group(base_path('routes/api.php'));
        Route::prefix('api/v1')
            ->middleware(['api'])
            ->namespace("$this->namespace\Api")
            ->name('api.')
            ->group(base_path('routes/public.php'));

        Route::prefix('api/v1')
            //->middleware(['api', 'auth:api'])
            //->middleware(['api', ])
            ->middleware(['auth:sanctum', 'api'])
            ->namespace("$this->namespace\Api\V1")
            ->name('api.')
            ->group(base_path('routes/api.php'));

        Route::prefix('client/api/v1')
            ->middleware(['api', 'client'])
            ->namespace("$this->namespace\Api\V1")
            ->name('client.')
            ->group(base_path('routes/api.php'));
    }

    protected function mapPrintRoutes()
    {
        Route::middleware(['web', 'api'])
            ->namespace("$this->namespace\Export")
            ->prefix('print')
            ->group(base_path('routes/print.php'));
    }
}
