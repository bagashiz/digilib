<?php

use App\Livewire\AddBook;
use App\Livewire\ListBooks;
use App\Livewire\Login;
use App\Livewire\Register;
use App\Livewire\ShowBook;
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

// User routes
Route::get('/settings', UserSettings::class)
    ->middleware('auth')->name('settings');

// Book routes
Route::get('/books', AddBook::class)
    ->middleware('auth')->name('books.create');
Route::get('/books/{uid}', ShowBook::class)
    ->middleware('auth')->name('books.show');
