<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\AnalyticsController;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\TaskController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\DeviceController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\VehicleController;
use App\Http\Controllers\Web\BeneficiaryController;
use App\Http\Controllers\Web\InitialViewController;
use App\Http\Controllers\Web\SimCardController;
use App\Http\Controllers\Web\TenantController;


use App\Http\Controllers\Web\Embeddable\EventEmbeddableController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
if (\in_array(env('APP_ENV', null), ['develop', 'production']) ) {
    $app_url = config("app.url");
    if (!empty($app_url)) {
        URL::forceRootUrl($app_url);
        $schema = explode(':', $app_url)[0];
        URL::forceScheme($schema);
    }
}

Route::get('/', [InitialViewController::class, 'index'])->middleware(['auth:sanctum']);

Route::get('profile', [UserController::class, 'profile'])->name('profile')->middleware(['permission:show-users']);

Route::prefix('users')->middleware(['auth:sanctum'])->group(function () {
    Route::get('', [UserController::class, 'index'])->name('users.index')->middleware(['permission:list-users']);
    Route::get('edit/{id}', [UserController::class, 'edit'])->name('users.edit')->middleware(['permission:edit-users']);
    Route::get('create', [UserController::class, 'create'])->name('users.create')->middleware(['permission:create-users']);
});

Route::prefix('device')->middleware(['auth:sanctum'])->group(function () {
    Route::get('', [DeviceController::class, 'index'])->name('devices.index')->middleware(['permission:list-users']);
    Route::get('edit/{id}', [DeviceController::class, 'edit'])->name('devices.edit')->middleware(['permission:edit-users']);
    Route::get('create', [DeviceController::class, 'create'])->name('devices.create')->middleware(['permission:create-users']);
});

Route::prefix('simcard')->middleware(['auth:sanctum'])->group(function () {
    Route::get('', [SimCardController::class, 'index'])->name('simcard.index')->middleware(['permission:list-users']);
    Route::get('edit/{id}', [SimCardController::class, 'edit'])->name('simcard.edit')->middleware(['permission:edit-users']);
    Route::get('create', [SimCardController::class, 'create'])->name('simcard.create')->middleware(['permission:create-users']);
});

Route::prefix('dashboard')->middleware(['auth:sanctum'])->group(function () {
    Route::get('', [DashboardController::class, 'index'])->name('dashboard.index');
});

Route::prefix('analytics')->middleware(['auth:sanctum'])->group(function () {
    Route::get('', [AnalyticsController::class, 'index'])->name('analytics.index');
});

Route::prefix('tenant')->middleware(['auth:sanctum'])->group(function () {
    Route::get('', [TenantController::class, 'index'])->name('tenant.index');
});

Route::prefix('notifications')->middleware(['auth:sanctum'])->group(function () {
    Route::get('', [NotificationController::class, 'index'])->name('notifications.index');
});

Route::prefix('vehicles')->middleware(['auth:sanctum'])->group(function () {
    Route::get('', [VehicleController::class, 'index'])->name('vehicles.index')->middleware(['permission:list-vehicles']);
    Route::get('create', [VehicleController::class, 'create'])->name('vehicles.create')->middleware(['permission:create-vehicles']);
    Route::get('show/{id}', [VehicleController::class, 'show'])->name('vehicles.show')->middleware(['permission:show-vehicles']);
    Route::get('summary', [VehicleController::class, 'summary'])->name('vehicles.summary')->middleware(['permission:show-vehicles']);
    Route::get('activate', [VehicleController::class, 'activate'])->name('vehicles.activate')->middleware(['permission:activate-vehicles']);
});

Route::prefix('beneficiaries')->middleware(['auth:sanctum'])->group(function () {
    Route::get('edit', [BeneficiaryController::class, 'edit'])->name('beneficiaries.edit')->middleware(['permission:edit-beneficiaries']);
});

/* Route::prefix('tasks')->middleware(['auth:sanctum'])->group(function () {
    Route::get('', [TaskController::class, 'index'])->name('tasks.index')->middleware(['permission:list-vehicles']);
}); */

Route::prefix('events')->middleware(['auth:sanctum'])->group(function () {
    Route::get('', [EventController::class, 'index'])->name('events.index')->middleware(['permission:list-events']);
    Route::get('show/{id}', [EventController::class, 'show'])->name('events.show')->middleware(['permission:show-events']);
    Route::get('create', [EventController::class, 'create'])->name('events.create')->middleware(['permission:create-events']);
});

Route::prefix('public/embeddable')->name('public.embeddable.')->group(function () {
    Route::get('latest-events', [EventEmbeddableController::class, 'latestEvents'])->name('latest-events');
    Route::get('events-per-day', [EventEmbeddableController::class, 'eventsPerDay'])->name('events-per-day');
    Route::get('events-per-hour', [EventEmbeddableController::class, 'eventsPerHour'])->name('events-per-hour');
    Route::get('events-per-model', [EventEmbeddableController::class, 'eventsPerModel'])->name('events-per-model');
    Route::get('events-status-result', [EventEmbeddableController::class, 'eventsStatusResult'])->name('events-status-result');

});

Auth::routes();

//Route::get('{tenant}/qr-devices/view', [\App\Http\Controllers\Export\DeviceQrController::class, 'view'])->name('exports.qr-devices.view');
//Route::get('{tenant}/qr-devices/pdf', [\App\Http\Controllers\Export\DeviceQrController::class, 'download'])->name('exports.qr-devices.pdf');
