<?php

use App\Http\Controllers\AI\AIController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DetectionTypeController;
use App\Http\Controllers\API\DroneController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\FlightController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\VerificationController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {

    Route::group(['middleware' => 'auth.apikey'], function () {
        Route::post('ai/events', [AIController::class, 'storeEvent']);
        Route::post('ai/store-flight', [AIController::class, 'storeFlight']);
        Route::put('ai/update-flight/{id}', [AIController::class, 'updateFlight']);
    });

    /*********************AuthController***************** */
    Route::post('login', [AuthController::class, 'login']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('user', [AuthController::class, 'user']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('users/actions', [UserController::class, 'actions']);
        Route::apiResource('users', UserController::class);
    });

    /*********************VerificationController***************** */
    Route::get('/verify/{id}/{hash}', [VerificationController::class, 'verify']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/verify', [VerificationController::class, 'send']);
    });

    /*********************RoleController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::apiResource('roles', RoleController::class);
    });

    /*********************PermissionController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('permissions', [PermissionController::class, 'permissions']);
        Route::apiResource('permission', PermissionController::class);
    });

    /*********************SettingController***************** */
    Route::get('general-setting', [SettingController::class, 'generalSettings']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('getSetting', [SettingController::class, 'get']);
        Route::get('settings', [SettingController::class, 'index']);
        Route::post('settings', [SettingController::class, 'update']);
        Route::post('set-setting', [SettingController::class, 'store']);
        // Route::apiResource('settings', SettingController::class)->except('update');
    });

    /*********************NotificationController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('notifications', [NotificationController::class, 'index']);
        Route::post('notifications', [NotificationController::class, 'update']);
    });

    /*********************DroneController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::apiResource('drones', DroneController::class);
        Route::get('get-drones/{locationId}', [DroneController::class, 'getDrones']);
    });

    /*********************EventController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('notes/{locationId}', [EventController::class, 'notes']);
        Route::get('types', [EventController::class, 'types']);
        Route::get('events/actions', [EventController::class, 'actions']);
        Route::get('events/{locationId}/cards', [EventController::class, 'cards']);
        Route::get('events/{locationId?}', [EventController::class, 'index']);
        Route::post('events/{id}/update', [EventController::class, 'update']);
        Route::get('live-mode/{locationId}', [EventController::class, 'getLiveMode']);
        Route::post('live-mode/{locationId}', [EventController::class, 'setLiveMode']);
    });

    /*********************LocationController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::apiResource('location', LocationController::class);
    });

    /*********************FlightController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('flight-data', [FlightController::class, 'flightData']);
        Route::get('flight-actions', [FlightController::class, 'actions']);
        Route::apiResource('flight', FlightController::class);
    });

    /*********************detectionController***************** */
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('detection-types-index', [DetectionTypeController::class, 'detectionTypes']);
        Route::get('detection-types/actions', [DetectionTypeController::class, 'actions']);
        Route::apiResource('detection-types', DetectionTypeController::class);
    });
});
