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

    Route::view('settings/profile', 'settings.profile')->name('settings.profile');
    Route::view('settings/password', 'settings.password')->name('settings.password');
    Route::view('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::middleware(['can:access-panel'])
        ->prefix('/painel')
        ->group(function () {
            Route::middleware(['can:users.index'])
                ->get('usuarios', function () {
                    return view('livewire.panel.users.index');
                })
                ->name('panel.users.index');

            Route::get('usuarios/create', function () {
                return redirect()->route('panel.users.index')->with('message', 'Funcionalidade em desenvolvimento');
            })->name('panel.users.create');

            Route::get('usuarios/{user}', function ($user) {
                return redirect()->route('panel.users.index')->with('message', 'Funcionalidade em desenvolvimento');
            })->name('panel.users.show');

            Route::get('usuarios/{user}/edit', function ($user) {
                return redirect()->route('panel.users.index')->with('message', 'Funcionalidade em desenvolvimento');
            })->name('panel.users.edit');
        });
});

require __DIR__ . '/auth.php';
