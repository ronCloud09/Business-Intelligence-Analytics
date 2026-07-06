<?php

use Illuminate\Support\Facades\Route;

// Home redirects to signin
Route::redirect('/', '/signin')->name('home');

// Sign-in page (no auth required)
Route::get('/signin', function () {
    return view('signIn');
})->name('signin');

// Contact us page
Route::get('/contactus', function () {
    return view('contactus');
})->name('contactus');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// AI Insights
Route::get('/ai-insights', function () {
    return view('ai-insights');
})->name('ai-insights');

// Login processing
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');