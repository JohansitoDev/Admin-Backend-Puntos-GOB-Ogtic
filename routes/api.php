<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\SuperAdmin\InstitutionController;
use App\Http\Controllers\Api\SuperAdmin\PuntoGOBController;
use App\Http\Controllers\Api\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\Api\SuperAdmin\ServiceController;
use App\Http\Controllers\Api\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Api\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Shared\ProfileController;
use App\Http\Controllers\Api\Shared\SupportTicketController;
use App\Http\Controllers\Api\Shared\HistoryController;
use App\Http\Controllers\Controllers\ActivityLogController;
use App\Http\Controllers\Api\Shared\ReportController;
use App\Http\Controllers\Api\SuperAdmin\AppointmentDashboardController;


Route::post('/login', [LoginController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'changePassword']); 

    
    Route::apiResource('support-tickets', SupportTicketController::class);

    
    Route::get('/history/appointments', [HistoryController::class, 'appointments']);
    Route::get('/activity-logs', [ActivityLogController::class, 'index']); 

    
    Route::middleware(['can:manage-all'])->prefix('superadmin')->name('superadmin.')->group(function () {
        
        Route::apiResource('institutions', InstitutionController::class);
        Route::apiResource('punto-gobs', PuntoGOBController::class); 
        Route::apiResource('users', SuperAdminUserController::class); 
        Route::apiResource('services', ServiceController::class);
        Route::get('dashboard/summary', [SuperAdminDashboardController::class, 'getSummary']);
        Route::get('dashboard/appointments-by-type', [SuperAdminDashboardController::class, 'getAppointmentsByType']);
        Route::get('dashboard/appointments-status-daily', [SuperAdminDashboardController::class, 'getAppointmentsStatusDaily']);
        Route::get('dashboard/institutions-summary', [SuperAdminDashboardController::class, 'getInstitutionsSummary']);
        Route::get('appointments', [AdminAppointmentController::class, 'indexForAll']); 
        Route::get('appointments/{appointment}', [AdminAppointmentController::class, 'showForAll']);
        Route::get('dashboard/external-appointments', [AppointmentDashboardController::class, 'getAppointments']);
        Route::get('dashboard/external-summary', [AppointmentDashboardController::class, 'getSummary']);
        Route::get('appointments/export-pdf', [ReportController::class, 'exportAppointmentsPdf']);
    });


    Route::middleware(['can:is-admin'])->prefix('admin')->name('admin.')->group(function () {
    
        Route::get('dashboard/summary', [AdminDashboardController::class, 'getSummary']);
        Route::get('dashboard/appointments-status-daily', [AdminDashboardController::class, 'getAppointmentsStatusDaily']); 
        Route::get('appointments', [AdminAppointmentController::class, 'indexForAdmin']);
        Route::get('appointments/{appointment}', [AdminAppointmentController::class, 'showForAdmin']);
        Route::put('appointments/{appointment}/process', [AdminAppointmentController::class, 'processAppointment']);
        Route::put('appointments/{appointment}/cancel', [AdminAppointmentController::class, 'cancelAppointment']);
        Route::get('appointments/export-pdf', [ReportController::class, 'exportAdminAppointmentsPdf']); 
    });
});