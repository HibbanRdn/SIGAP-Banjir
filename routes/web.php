<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/', 'admin.dashboard')->name('dashboard');
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard.index');
});
