<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth'])
    ->group(function () {
        Route::view('dashboard', 'dashboard')
            ->middleware(['verified'])
            ->name('dashboard');

        Route::view('profile', 'profile')
            ->name('profile');

        Route::middleware(['auth', 'can:users.index'])
            ->prefix('panel')
            ->group(function () {
                Route::view('users', 'livewire.users')
                    ->name('users');
            })
            ->name('panel');
    });

require __DIR__ . '/auth.php';
