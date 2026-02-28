
<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Dashboard\JobSeekerDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes - WorkNepal Job Platform
|--------------------------------------------------------------------------
|
| Public routes first → authentication → protected/authenticated
|
*/

// ────────────────────────────────────────────────
// 1. Public / Guest Routes (no auth required)
// ────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('jobs')->name('jobs.')->group(function () {
    Route::get('/', [JobController::class, 'index'])->name('index');
    Route::get('/{job:slug}', [JobController::class, 'show'])->name('show');
});

Route::prefix('companies')->name('companies.')->group(function () {
    Route::get('/{company:slug}', [CompanyController::class, 'show'])->name('show');
});

Route::prefix('pages')->name('pages.')->group(function () {
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [PageController::class, 'terms'])->name('terms');
    Route::get('/cv-tips', [PageController::class, 'cvTips'])->name('cv-tips');
    Route::get('/foreign-safety', [PageController::class, 'foreignSafety'])->name('foreign-safety');
});

// ────────────────────────────────────────────────
// 2. Breeze Authentication Routes
// ────────────────────────────────────────────────

require __DIR__.'/auth.php';

require __DIR__.'/verification.php';
// ────────────────────────────────────────────────
// 4. Protected / Authenticated Routes (require login + verified + active)
// ────────────────────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Job Seeker Routes
    Route::middleware('role:job_seeker')->group(function () {
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/', [JobSeekerDashboardController::class, 'index'])->name('jobseeker');
        });
    });

    // Employer Routes
    Route::middleware(['role:employer', 'employer'])->prefix('employer')->name('employer.')->group(function () {
        // Add your employer routes here
    });

    // Admin Routes
    Route::middleware('role:admin|super_admin')->prefix('admin')->name('admin.')->group(function () {
        // Add your admin routes here
    });
});