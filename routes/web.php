<?php

use App\Http\Controllers\AIChatController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\AIInsightsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentAnalyticsController;
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
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// AI Insights
Route::get('/ai-insights', [AIInsightsController::class, 'index'])->name('ai-insights');

// Department Health
Route::get('/department-analytics', [DepartmentAnalyticsController::class, 'index'])->name('department-analytics');

// Login processing
Route::post('/login', [LoginController::class, 'login'])->name('login');

// NEXORA AI — foundation endpoints (Package 1-2). Full Intelligence
// Center UI and manual refresh button are wired in later packages.
Route::prefix('nexora-ai')->name('ai.')->group(function () {
    Route::get('/current-report', [AIController::class, 'current'])->name('current');
    Route::post('/refresh', [AIController::class, 'refresh'])->name('refresh');
    Route::post('/chat', [AIChatController::class, 'respond'])->name('chat');
});
