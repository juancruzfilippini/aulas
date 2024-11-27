<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/create-event', [EventController::class, 'createEvent'])->name('events.register');

Route::post('/events', [EventController::class, 'storeEvent'])->name('events.storeEvent');

Route::get('/manage-event', [EventController::class, 'manageEvent'])->name('events.manage');

Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');

Route::post('/events/bulk-update', [EventController::class, 'bulkUpdate'])->name('events.bulkUpdate');

