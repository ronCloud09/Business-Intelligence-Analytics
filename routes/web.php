<?php

use App\Http\Controllers\AIChatController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\AIInsightsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

use App\Services\DataService;

// Home redirects to signin
Route::redirect('/', '/signin')->name('home');

// Sign-in page
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
Route::get('/ai-insights', [AIInsightsController::class, 'index'])
    ->name('ai-insights');

// Department Analytics
Route::get('/department-analytics', function () {
    return view('department-analytics', [
        'departments' => DataService::getDepartmentList(),
    ]);
})->name('department-analytics');

// Department Analytics API
Route::get('/api/department/{dept}', function ($dept) {
    return response()->json(DataService::getDepartment($dept));
});

// Login processing
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');

// NEXORA AI — foundation endpoints
Route::prefix('nexora-ai')->name('ai.')->group(function () {
    Route::get('/current-report', [AIController::class, 'current'])->name('current');
    Route::post('/refresh', [AIController::class, 'refresh'])->name('refresh');
    Route::post('/chat', [AIChatController::class, 'respond'])->name('chat');
});