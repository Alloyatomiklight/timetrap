<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Time\TrackingController;
use App\Http\Controllers\Auth\LoginRegisterController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/time-tracking', [TrackingController::class, 'index'])->name('time-tracking.index');
    Route::get('/time-tracking/show', [TrackingController::class, 'show'])->name('time-tracking.show');
    Route::get('/time-tracking/create', [TrackingController::class, 'create'])->name('time-tracking.create');
    Route::post('/time-tracking/store', [TrackingController::class, 'store'])->name('time-tracking.store');
    Route::get('/time-tracking/edit{id}', [TrackingController::class, 'edit'])->name('time-tracking.edit');
    Route::post('time-tracking/{id}', [TrackingController::class, 'update'])->name('time-tracking.update');
    Route::delete('/time-tracking/destroy', [TrackingController::class, 'destroy'])->name('time-tracking.destroy');
    Route::get('/time-tracking/export', [TrackingController::class, 'export'])->name('time-tracking.export');
});

Route::controller(LoginRegisterController::class)->group(function() {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/logout', 'logout')->name('logout');
});
