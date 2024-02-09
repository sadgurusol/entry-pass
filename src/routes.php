<?php
use Illuminate\Support\Facades\Route;


Route::get('/login', [\Sadguru\SGEntryPass\SGLoginController::class, 'login'])->name('login');
Route::get('/login-token', [\Sadguru\SGEntryPass\SGLoginController::class, 'authenticateLoginLink'])->name('login-token');
Route::get('/login-link', [\Sadguru\SGEntryPass\SGLoginController::class, 'loginWithLink'])->name('login-link');
Route::post('/login-by-email', [\Sadguru\SGEntryPass\SGLoginController::class, 'authenticateUserByEmail']);
Route::post('/login', [\Sadguru\SGEntryPass\SGLoginController::class, 'authenticateUser']);
Route::post('/register', [\Sadguru\SGEntryPass\SGLoginController::class, 'createNewAccount']);
Route::post('/login-link', [\Sadguru\SGEntryPass\SGLoginController::class, 'sendLoginLink']);
Route::get('/logout', [\Sadguru\SGEntryPass\SGLoginController::class, 'logout'])->name('logout');
Route::get('/register', [\Sadguru\SGEntryPass\SGLoginController::class, 'register'])->name('register');

Route::get('/forgot-password', [\Sadguru\SGEntryPass\SGLoginController::class, 'forgotPassword'])->name('forgot-password');
Route::get('/reset-password', [\Sadguru\SGEntryPass\SGLoginController::class, 'resetPassword'])->name('reset-password');
Route::post('/password-reset-link', [\Sadguru\SGEntryPass\SGLoginController::class, 'sendPasswordResetLink'])->name('password-reset-link');
Route::post('/create-password', [\Sadguru\SGEntryPass\SGLoginController::class, 'createPassword'])->name('create-password');


