<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;

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


Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth'])->group(function () {

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

    Route::middleware('role:job_seeker')->prefix('seeker')->group(function () {
        Route::get('/dashboard', [SeekerDashboardController::class, 'index'])->name('seeker.dashboard');
    });

    // Employer area – must belong to at least one company
    Route::middleware('employer')->prefix('employer')->group(function () {
        Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('employer.dashboard');
        Route::resource('companies', CompanyController::class)->except(['show']);
    });

});