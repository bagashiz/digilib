<?php

use App\Livewire\ListBooks;
use App\Livewire\Login;
use App\Livewire\Register;
use App\Livewire\UserSettings;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', ListBooks::class)
    ->middleware('auth')->name('home');

// Authentication routes
Route::get('/register', Register::class)
    ->middleware('guest')->name('register');
Route::get('/login', Login::class)
    ->middleware('guest')->name('login');
Route::get('/logout', function () {
    auth()->logout();
    return redirect('login');
})->middleware('auth')->name('logout');

// User routes
Route::get('/settings', UserSettings::class)
    ->middleware('auth')->name('settings');
