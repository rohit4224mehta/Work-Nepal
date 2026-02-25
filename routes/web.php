<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PageController;     // for static pages
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - WorkNepal Job Platform
|--------------------------------------------------------------------------
|
| Public (guest) routes first → then authenticated → then role-specific
| All routes are web middleware group by default
|
*/

// ────────────────────────────────────────────────
// 1. Public / Guest Routes (no auth required)
// ────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Job Search & Details (Phase 3 of your plan)
Route::prefix('jobs')->name('jobs.')->group(function () {
    Route::get('/', [JobController::class, 'index'])->name('index');           // list + filters
    Route::get('/{job:slug}', [JobController::class, 'show'])->name('show');   // detail page
});

// Public Company Profiles (future)
Route::prefix('companies')->name('companies.')->group(function () {
    Route::get('/{company:slug}', [CompanyController::class, 'show'])->name('show');
});

// Static / Informational Pages
Route::prefix('pages')->name('pages.')->group(function () {
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [PageController::class, 'terms'])->name('terms');
    Route::get('/cv-tips', [PageController::class, 'cvTips'])->name('cv-tips');
    Route::get('/foreign-safety', [PageController::class, 'foreignSafety'])->name('foreign-safety');
});

// ────────────────────────────────────────────────
// 2. Authentication Routes (included from Breeze)
// ────────────────────────────────────────────────

require __DIR__.'/auth.php';

// ────────────────────────────────────────────────
// 3. Authenticated Routes (must be logged in)
// ────────────────────────────────────────────────

Route::middleware(['auth', 'verified', 'account.active'])->group(function () {

    // Profile (Breeze default – keep for now)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Temporary / fallback dashboard (will be replaced by role-based redirect)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ────────────────────────────────────────────────
    // Role-based routes (expand in later phases)
    // ────────────────────────────────────────────────

    // Job Seeker Routes (Phase 2, 4, etc.)
    Route::middleware('role:job_seeker')->prefix('seeker')->name('seeker.')->group(function () {
        // Route::get('/profile/complete', ...)->name('profile.complete');
        // Route::get('/applications', ...)->name('applications.index');
        // Route::get('/bookmarks', ...)->name('bookmarks.index');
    });

    // Employer Routes (Phase 5)
    Route::middleware(['role:employer', 'employer'])->prefix('employer')->name('employer.')->group(function () {
        // Route::resource('jobs', EmployerJobController::class)->except(['show']);
        // Route::get('/company/create', ...)->name('company.create');
        // Route::get('/applicants', ...)->name('applicants.index');
    });

    // Admin Routes (Phase 6)
    Route::middleware('role:admin|super_admin')->prefix('admin')->name('admin.')->group(function () {
        // Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        // Route::get('/jobs/pending', ...)->name('jobs.pending');
        // Route::post('/jobs/{job}/approve', ...)->name('jobs.approve');
    });
});