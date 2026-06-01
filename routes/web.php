<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\DataSourceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EquipmentInventoryController;
use App\Http\Controllers\Admin\EquipmentTypeController;
use App\Http\Controllers\Admin\FloodEventController;
use App\Http\Controllers\Admin\FloodRiskPointController;
use App\Http\Controllers\Admin\EvacuationPointController;
use App\Http\Controllers\Admin\HeavyEquipmentPostController;
use App\Http\Controllers\Admin\HeavyEquipmentUnitController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::view('/peta', 'pages.home')->name('map');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.store');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('auth')->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::redirect('/', '/admin/dashboard')->name('home');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('flood-events', FloodEventController::class);
        Route::resource('flood-risks', FloodRiskPointController::class);
        Route::resource('evacuation-points', EvacuationPointController::class);
        Route::resource('heavy-equipment-posts', HeavyEquipmentPostController::class);
        Route::get('/equipment', EquipmentInventoryController::class)->name('equipment.index');
        Route::resource('equipment-types', EquipmentTypeController::class);
        Route::resource('heavy-equipment-units', HeavyEquipmentUnitController::class);
        Route::get('/data-sources', [DataSourceController::class, 'index'])->name('data-sources.index');
    });
});
