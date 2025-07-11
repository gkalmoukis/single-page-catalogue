<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\QrCodeController;
use App\Services\QrCodeService;
use App\Models\Tenant;

// Main domain route - redirect to admin or show info page
Route::get('/', function () {
    return redirect('/admin');
})->name('home');

// QR Code download route for admin - using dedicated controller
Route::get('/admin/tenants/{tenant}/download-qr', [QrCodeController::class, 'download'])
    ->name('filament.admin.resources.tenants.download-qr')
    ->middleware(['web', 'auth']);

// Path-based tenant routes - only way to access tenants
Route::prefix('t/{tenant_slug}')->group(function () {
    Route::get('/', [RestaurantController::class, 'show'])->name('tenant.restaurant.show');
    Route::get('/admin', function() {
        return redirect('/admin');
    })->name('tenant.admin');
});

// All other routes will be handled by tenant-specific context through middleware
