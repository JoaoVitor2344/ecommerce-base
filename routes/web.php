<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::middleware(['can:manage-panel'])
        ->prefix('panel')
        ->name('panel.')
        ->group(function () {
           Route::middleware(['can:manage-permissions'])
                ->prefix('permissions')
                ->name('permissions.')
                ->group(function () {
                    Volt::route('/', 'panel.permission.index')
                        ->middleware(['can:view-permissions'])
                        ->name('index');
                });

            Route::middleware(['can:manage-roles'])
                ->prefix('roles')
                ->name('roles.')
                ->group(function () {
                    Volt::route('/', 'panel.role.index')
                        ->middleware(['can:view-roles'])
                        ->name('index');
                    
                    Route::get('/create', function () {
                        return response()->json(['message' => 'Rota ainda não implementada']);
                    })->name('create');
                    
                    Route::get('/{role}/edit', function () {
                        return response()->json(['message' => 'Rota ainda não implementada']);
                    })->name('edit');
                });

            Route::middleware(['can:manage-users'])
                ->prefix('users')
                ->name('users.')
                ->group(function () {
                    Route::get('/', function () {
                        return response()->json(['message' => 'Rota ainda não implementada']);
                    })->name('index');
                    
                    Route::get('/create', function () {
                        return response()->json(['message' => 'Rota ainda não implementada']);
                    })->name('create');
                    
                    Route::get('/{user}/edit', function () {
                        return response()->json(['message' => 'Rota ainda não implementada']);
                    })->name('edit');
                });
        });
});

require __DIR__.'/auth.php';
