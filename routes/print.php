<?php

use Illuminate\Support\Facades\Route;

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
Route::get('{tenant}/qr-devices/view', [\App\Http\Controllers\Export\DeviceQrController::class, 'view'])->name('exports.qr-devices.view');
Route::get('{tenant}/qr-devices/pdf', [\App\Http\Controllers\Export\DeviceQrController::class, 'download'])->name('exports.qr-devices.pdf');
