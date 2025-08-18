<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('member.login');
});

// Member Authentication Routes (Frontend)
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Legacy routes for backward compatibility
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes for Members (Frontend)
Route::middleware('auth:member')->group(function () {
    Route::resource('todos', App\Http\Controllers\TodoController::class);
    
    // Invitation routes
    Route::get('/invitations', [App\Http\Controllers\InvitationController::class, 'index'])->name('invitations.index');
    Route::post('/todos/{todo}/invite', [App\Http\Controllers\InvitationController::class, 'store'])->name('invitations.store');
    Route::post('/invitations/{invitation}/accept', [App\Http\Controllers\InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/{invitation}/reject', [App\Http\Controllers\InvitationController::class, 'reject'])->name('invitations.reject');
    Route::delete('/invitations/{invitation}', [App\Http\Controllers\InvitationController::class, 'cancel'])->name('invitations.cancel');
    Route::delete('/todos/{todo}/collaborators', [App\Http\Controllers\InvitationController::class, 'removeCollaborator'])->name('collaborators.remove');
});
