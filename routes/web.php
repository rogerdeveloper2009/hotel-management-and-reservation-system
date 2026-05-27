<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('role:super_admin,admin')->group(function () {
        Route::resource('room-types', RoomTypeController::class)->except(['show']);
        Route::resource('rooms', RoomController::class);
        Route::resource('amenities', AmenityController::class)->except(['show']);
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });

    Route::middleware('role:super_admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    Route::middleware('role:super_admin,admin,receptionist,manager')->group(function () {
        Route::resource('customers', CustomerController::class);
        Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
        Route::post('bookings/{booking}/check-in', [BookingController::class, 'checkIn'])->name('bookings.checkin');
        Route::post('bookings/{booking}/check-out', [BookingController::class, 'checkOut'])->name('bookings.checkout');
        Route::resource('bookings', BookingController::class)->except(['destroy']);
        Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store']);

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/daily.pdf', [ReportController::class, 'dailyPdf'])->name('reports.daily.pdf');
        Route::get('reports/daily.xlsx', [ReportController::class, 'dailyExcel'])->name('reports.daily.xlsx');
    });
});

require __DIR__.'/auth.php';
