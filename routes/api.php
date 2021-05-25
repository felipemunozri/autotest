<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V1\BeneficiaryController;
use App\Http\Controllers\Api\V1\BeneficiaryVehicleController;
use App\Http\Controllers\Api\V1\CarBrandController;
use App\Http\Controllers\Api\V1\CarrierController;
use App\Http\Controllers\Api\V1\DashboardSummaryController;
use App\Http\Controllers\Api\V1\RolesController;
use App\Http\Controllers\Api\V1\CountryController;
use App\Http\Controllers\Api\V1\DeviceActivationController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\DeviceModelController;
use App\Http\Controllers\Api\V1\DeviceStatusController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\EventQuestionController;
use App\Http\Controllers\Api\V1\EventQuestionAppliedController;
use App\Http\Controllers\Api\V1\EventStatusController;
use App\Http\Controllers\Api\V1\EventResultController;
use App\Http\Controllers\Api\V1\EventSummaryController;
use App\Http\Controllers\Api\V1\FuelTypeController;
use App\Http\Controllers\Api\V1\OperationSmsController;
use App\Http\Controllers\Api\V1\VehicleController;
use App\Http\Controllers\Api\V1\TenantController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ValidateController;
use App\Http\Controllers\Api\V1\SimCardController;
use App\Http\Controllers\Api\V1\EventCallController;
use App\Http\Controllers\Api\V1\UserNotificationController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\SimCardStatusController;
use App\Http\Controllers\Api\V1\Reports\VehicleRankingController;
use App\Http\Controllers\Api\V1\MetabaseReportController;
use App\Helpers\DeviceSchemas\GPS103\GPS103Schema;

use App\Http\Controllers\Export\VehicleRankingReportController;
use App\Http\Controllers\Export\EmbeddableController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('carriers')
    ->name('carriers.')
    ->group(function() {
        Route::get('{carrier}', [CarrierController::class, 'show'])->name('show');
        Route::post('', [CarrierController::class, 'store'])->name('store');
        Route::patch('{carrier}', [CarrierController::class, 'update'])->name('update');
        Route::delete('{carrier}', [CarrierController::class, 'delete'])->name('delete');
        Route::get('', [CarrierController::class, 'index'])->name('index');
    });

Route::prefix('roles')
    ->name('roles.')
    ->group(function() {
        Route::get('', [RolesController::class, 'index'])->name('index');
    });

Route::prefix('car-brands')
    ->name('car-brands.')
    ->group(function() {
        Route::get('{carBrand}', [CarBrandController::class, 'show'])->name('show');
        Route::post('', [CarBrandController::class, 'store'])->name('store');
        Route::patch('{carBrand}', [CarBrandController::class, 'update'])->name('update');
        Route::delete('{carBrand}', [CarBrandController::class, 'delete'])->name('delete');
        Route::get('', [CarBrandController::class, 'index'])->name('index');
    });

Route::prefix('countries')
    ->name('countries.')
    ->group(function() {
        Route::get('{country}', [CountryController::class, 'show'])->name('show');
        Route::post('', [CountryController::class, 'store'])->name('store');
        Route::patch('{country}', [CountryController::class, 'update'])->name('update');
        Route::delete('{country}', [CountryController::class, 'delete'])->name('delete');
        Route::get('', [CountryController::class, 'index'])->name('index');
    });

Route::prefix('device-models')
    ->name('device-models.')
    ->group(function() {
        Route::get('{deviceModel}', [DeviceModelController::class, 'show'])->name('show');
        Route::post('', [DeviceModelController::class, 'store'])->name('store');
        Route::patch('{deviceModel}', [DeviceModelController::class, 'update'])->name('update');
        Route::delete('{deviceModel}', [DeviceModelController::class, 'delete'])->name('delete');
        Route::get('', [DeviceModelController::class, 'index'])->name('index');
    });

Route::prefix('devices')
    ->name('devices.')
    ->group(function() {
        Route::post('activation', [DeviceActivationController::class, 'store'])->name('activation');
        Route::get('{device}', [DeviceController::class, 'show'])->name('show');
        Route::post('', [DeviceController::class, 'store'])->name('store');
        Route::patch('{device}', [DeviceController::class, 'update'])->name('update');
        Route::delete('{device}', [DeviceController::class, 'delete'])->name('delete');
        Route::get('', [DeviceController::class, 'index'])->name('index');
    });

Route::prefix('device-status')
    ->name('device-status.')
    ->group(function() {
        Route::get('{deviceStatus}', [DeviceStatusController::class, 'show'])->name('show');
        Route::post('', [DeviceStatusController::class, 'store'])->name('store');
        Route::patch('{deviceStatus}', [DeviceStatusController::class, 'update'])->name('update');
        Route::delete('{deviceStatus}', [DeviceStatusController::class, 'delete'])->name('delete');
        Route::get('', [DeviceStatusController::class, 'index'])->name('index');
    });

Route::prefix('fuel-types')
    ->name('fuel-types.')
    ->group(function() {
        Route::get('{country}', [FuelTypeController::class, 'show'])->name('show');
        Route::post('', [FuelTypeController::class, 'store'])->name('store');
        Route::patch('{country}', [FuelTypeController::class, 'update'])->name('update');
        Route::delete('{country}', [FuelTypeController::class, 'delete'])->name('delete');
        Route::get('', [FuelTypeController::class, 'index'])->name('index');
    });

Route::prefix('tenants')
    ->name('tenants.')
    ->group(function() {
        Route::get('{tenant}', [TenantController::class, 'show'])->name('show');
        Route::post('', [TenantController::class, 'store'])->name('store');
        Route::patch('{tenant}', [TenantController::class, 'update'])->name('update');
        Route::delete('{tenant}', [TenantController::class, 'delete'])->name('delete');
        Route::post('{tenant}', [TenantController::class, 'uploadProfilePhoto'])->name('upload-photo');
        Route::get('', [TenantController::class, 'index'])->name('index');
    });

Route::prefix('metabase-reports')
    ->name('metabase-reports.')
    ->group(function() {
        Route::get('', [MetabaseReportController::class, 'index'])->name('index');
        Route::post('', [MetabaseReportController::class, 'store'])->name('store');
        Route::patch('{metabaseReport}', [MetabaseReportController::class, 'update'])->name('update');
    });

Route::prefix('users')
    ->name('users.')
    ->group(function() {
        Route::get('{user}', [UserController::class, 'show'])->name('show');
        Route::post('', [UserController::class, 'store'])->name('store');
        Route::patch('{user}', [UserController::class, 'update'])->name('update');
        Route::delete('{user}', [UserController::class, 'delete'])->name('delete');
        Route::get('', [UserController::class, 'index'])->name('index');
        Route::post('{user}', [UserController::class, 'uploadProfilePhoto'])->name('upload-photo');
    });

Route::prefix('logged-user')
    ->name('logged-user.')
    ->group(function() {
        Route::get('current-tenant', [UserController::class, 'currentTenant'])->name('current-tenant');
    });

Route::prefix('user')
    ->name('user.')
    ->group(function() {

        Route::prefix('notifications')
            ->name('notifications.')
            ->group(function() {
                Route::get('', [UserNotificationController::class, 'index'])->name('index');
                Route::patch('', [UserNotificationController::class, 'read'])->name('read');
                Route::delete('', [UserNotificationController::class, 'delete'])->name('delete');
            });
    });

Route::prefix('beneficiaries')
    ->name('beneficiaries.')
    ->group(function() {
        Route::get('{beneficiary}', [BeneficiaryController::class, 'show'])->name('show');
        Route::post('', [BeneficiaryController::class, 'store'])->name('store');
        Route::patch('{beneficiary}', [BeneficiaryController::class, 'update'])->name('update');
        Route::delete('{beneficiary}', [BeneficiaryController::class, 'delete'])->name('delete');
        Route::get('', [BeneficiaryController::class, 'index'])->name('index');
    });

Route::prefix('vehicles')
    ->name('vehicles.')
    ->group(function() {
        Route::get('{vehicle}', [VehicleController::class, 'show'])->name('show');
        Route::post('', [VehicleController::class, 'store'])->name('store');
        Route::patch('{vehicle}', [VehicleController::class, 'update'])->name('update');
        Route::delete('{vehicle}', [BeneficiaryController::class, 'delete'])->name('delete');
        Route::get('', [VehicleController::class, 'index'])->name('index');
    });

Route::prefix('beneficiary-vehicle')
    ->name('beneficiary-vehicle.')
    ->group(function() {
        Route::get('{vehicle}', [BeneficiaryVehicleController::class, 'show'])->name('show');
        Route::post('', [BeneficiaryVehicleController::class, 'store'])->name('store');
        Route::patch('', [BeneficiaryVehicleController::class, 'update'])->name('update');
        Route::delete('', [BeneficiaryVehicleController::class, 'delete'])->name('delete');
        Route::get('', [BeneficiaryVehicleController::class, 'index'])->name('index');
    });

Route::prefix('sim-cards')
    ->name('sim-cards.')
    ->group(function() {
        Route::get('{simCard}', [SimCardController::class, 'show'])->name('show');
        Route::post('', [SimCardController::class, 'store'])->name('store');
        Route::patch('{simCard}', [SimCardController::class, 'update'])->name('update');
        Route::delete('{simCard}', [SimCardController::class, 'delete'])->name('delete');
        Route::get('', [SimCardController::class, 'index'])->name('index');
    });

Route::prefix('simcard-status')
    ->name('simcard-status.')
    ->group(function() {
        Route::get('{simCardStatus}', [SimCardStatusController::class, 'show'])->name('show');
        Route::post('', [SimCardStatusController::class, 'store'])->name('store');
        Route::patch('{simCardStatus}', [SimCardStatusController::class, 'update'])->name('update');
        Route::delete('{simCardStatus}', [SimCardStatusController::class, 'delete'])->name('delete');
        Route::get('', [SimCardStatusController::class, 'index'])->name('index');
    });

Route::prefix('events')
    ->name('events.')
    ->group(function() {
        Route::get('{event}', [EventController::class, 'show'])->name('show');
        Route::post('', [EventController::class, 'store'])->name('store');
        Route::patch('{event}', [EventController::class, 'update'])->name('update');
        Route::delete('{event}', [EventController::class, 'delete'])->name('delete');
        Route::get('', [EventController::class, 'index'])->name('index');
        Route::post('find-in-progress', [EventController::class, 'findInProgress'])->name('find-in-progress');
        Route::post('validate-code', [EventController::class, 'validateCode'])->name('validate');
        Route::post('leave', [EventController::class, 'leaveEvent'])->name('leave');
        Route::post('finish', [EventController::class, 'finishEvent'])->name('finish');
    });

Route::prefix('event-calls')
    ->name('event-calls.')
    ->group(function() {
        Route::get('{eventCall}', [EventCallController::class, 'show'])->name('show');
        Route::post('', [EventCallController::class, 'store'])->name('store');
        Route::patch('{eventCall}', [EventCallController::class, 'update'])->name('update');
        Route::delete('{eventCall}', [EventCallController::class, 'delete'])->name('delete');
        Route::get('', [EventCallController::class, 'index'])->name('index');
        Route::patch('{eventCall}/finish-call', [EventCallController::class, 'finishCall'])->name('finish');
    });

Route::prefix('events-status')
    ->name('events-status.')
    ->group(function() {
        Route::get('', [EventStatusController::class, 'index'])->name('index');
    });

Route::prefix('events-results')
    ->name('events-results.')
    ->group(function() {
        Route::get('', [EventResultController::class, 'index'])->name('index');
    });

Route::prefix('event-questions-applied')
    ->name('event-questions-applied.')
    ->group(function() {
        Route::get('{eventQuestionApplied}', [EventQuestionAppliedController::class, 'show'])->name('show');
        Route::post('', [EventQuestionAppliedController::class, 'store'])->name('store');
        Route::post('store-list', [EventQuestionAppliedController::class, 'storeList'])->name('store-list');
        Route::post('update-or-store', [EventQuestionAppliedController::class, 'updateOrStore'])->name('update-or-store');
        Route::patch('{eventQuestionApplied}', [EventQuestionAppliedController::class, 'update'])->name('update');
        Route::delete('{eventQuestionApplied}', [EventQuestionAppliedController::class, 'delete'])->name('delete');
        Route::get('', [EventQuestionAppliedController::class, 'index'])->name('index');
    });

Route::prefix('event-questions')
    ->name('event-questions.')
    ->group(function() {
        Route::get('{eventQuestion}', [EventQuestionController::class, 'show'])->name('show');
        Route::post('', [EventQuestionController::class, 'store'])->name('store');
        Route::patch('{eventQuestion}', [EventQuestionController::class, 'update'])->name('update');
        Route::delete('{eventQuestion}', [EventQuestionController::class, 'delete'])->name('delete');
        Route::get('', [EventQuestionController::class, 'index'])->name('index');
    });

Route::prefix('summary')
    ->name('summary.')
    ->group(function() {
        Route::get('dashboard', [DashboardSummaryController::class, 'index'])->name('dashboard');
        Route::get('events', [EventSummaryController::class, 'index'])->name('events');
        Route::get('vehicle-ranking', [VehicleRankingController::class, 'index'])->name('vehicle-ranking');
    });

Route::prefix('tasks')
    ->name('tasks.')
    ->group(function() {
        Route::get('{task}', [TaskController::class, 'show'])->name('show');
        Route::post('', [TaskController::class, 'store'])->name('store');
        Route::patch('{task}', [TaskController::class, 'update'])->name('update');
        Route::delete('{task}', [TaskController::class, 'delete'])->name('delete');
        Route::get('', [TaskController::class, 'index'])->name('index');
    });

Route::prefix('operation-sms')
    ->name('operation-sms.')
    ->group(function() {
        Route::post('send/{vehicle}', [OperationSmsController::class, 'send'])->name('send');
        Route::post('send-code', [OperationSmsController::class, 'sendConfirmationCode'])->name('send-code');
    });

Route::prefix('validate')
    ->name('validate.')
    ->group(function() {
        Route::post('check', [ValidateController::class, 'check'])->name('check');
        Route::post('code/send/{to}', [ValidateController::class, 'send'])->name('code.send');
    });

Route::get('/call/{to}', function(Request $request, $to){
    $sid = "ACbef195bf21b9fccb0e82cf68cb59cd52"; // Your Account SID from www.twilio.com/console
    $token = "e79ed6f699d59bb1df307cb8f7f7de30"; // Your Auth Token from www.twilio.com/console
    $from = "+56227125422";
    $client = new Twilio\Rest\Client($sid, $token);
    $message = $client->calls->create(
        $to, // to
        $from, // from
        [
            "twiml" => "<Response><Say></Say></Response>"
        ]
        );
    $data = [
        'action' => 'Send Message',
        'from' => $from,
        'to' => $to,
        'timestamp' => Carbon\Carbon::now()->format('Y-m-d g:i A'),
        'message_sid' => $message->sid
    ];
    Log::info($data);
    return response()->json($data, 200);
});


Route::group([
    'prefix' => 'public',
    'excluded_middleware' => ['auth:sanctum'],
], function () {
    Route::post('operation-sms/receive', [OperationSmsController::class, 'receive'])->name('receive');
    Route::post('user/token', [UserController::class, 'createPersonalAccessToken'])->name('user.create-token');
    Route::post('/password/email', [ForgotPasswordController::class, 'send']);
    Route::post('/password/reset', [ResetPasswordController::class, 'store']);
    Route::prefix('exports')
        ->name('exports.')
        ->group(function() {
            //Route::get('{tenant}/qr-devices/view', [\App\Http\Controllers\Export\DeviceQrController::class, 'view'])->name('qr-devices.view');
            Route::get('{tenant}/qr-devices/pdf', [\App\Http\Controllers\Export\DeviceQrController::class, 'download'])->name('qr-devices.pdf');

            Route::prefix('vehicle-ranking')
                ->name('vehicle-ranking.')
                ->group(function() {
                    Route::get('{tenant}/view', [VehicleRankingReportController::class, 'view'])->name('view');
                    Route::get('{tenant}/pdf', [VehicleRankingReportController::class, 'download'])->name('pdf');
                });
            Route::prefix('embeddable')
                ->name('embeddable.')
                ->group(function() {
                    Route::get('view', [EmbeddableController::class, 'view'])->name('view');
                    Route::get('pdf', [EmbeddableController::class, 'download'])->name('pdf');
                });
        });
});


