<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\FloodEventController;
use App\Http\Controllers\Admin\FloodRiskPointController;
use App\Http\Controllers\Admin\EvacuationPointController;
use App\Http\Controllers\Admin\HeavyEquipmentPostController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::view('/peta', 'pages.home')->name('map');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.store');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('auth')->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::redirect('/', '/admin/dashboard')->name('home');
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        Route::resource('flood-events', FloodEventController::class);
        Route::resource('flood-risks', FloodRiskPointController::class);
        Route::resource('evacuation-points', EvacuationPointController::class);
        Route::resource('heavy-equipment-posts', HeavyEquipmentPostController::class);
        Route::view('/equipment', 'admin.equipment.index')->name('equipment.index');
        Route::view('/spatial-analysis', 'admin.spatial-analysis.index')->name('spatial-analysis.index');
        Route::view('/routes/preview', 'admin.routes.preview')->name('routes.preview');
        Route::view('/data-sources', 'admin.data-sources.index')->name('data-sources.index');
        Route::view('/ui-states', 'admin.ui-states.index')->name('ui-states.index');
    });
});
