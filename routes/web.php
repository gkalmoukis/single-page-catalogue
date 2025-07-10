<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;

// Main domain route - redirect to admin or show info page
Route::get('/', function () {
    return redirect('/admin');
})->name('home');

// Path-based tenant routes - only way to access tenants
Route::prefix('t/{tenant_slug}')->group(function () {
    Route::get('/', [RestaurantController::class, 'show'])->name('tenant.restaurant.show');
    Route::get('/admin', function() {
        return redirect('/admin');
    })->name('tenant.admin');
});

// All other routes will be handled by tenant-specific context through middleware
