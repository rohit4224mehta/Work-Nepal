<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Dashboard\JobSeekerDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobSeeker\SavedJobController;

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - WorkNepal Job Platform
|--------------------------------------------------------------------------
|
| 1. Public routes (guest access)
| 2. Authentication routes (login, register, password reset)
| 3. Email verification routes
| 4. Protected routes (login + email verified required)
| 5. Role-based dashboards & features
|
*/


// ────────────────────────────────────────────────
// 1. Public / Guest Routes (no auth required)
// ────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');

// Jobs (public search & view)
Route::prefix('jobs')->name('jobs.')->group(function () {
    Route::get('/', [JobController::class, 'index'])->name('index');
    Route::get('/{job:slug}', [JobController::class, 'show'])->name('show');
});

// Companies (public profiles)
Route::prefix('companies')->name('companies.')->group(function () {
    Route::get('/{company:slug}', [CompanyController::class, 'show'])->name('show');
});

// Static Pages

// Pages Routes
Route::prefix('pages')->name('pages.')->group(function () {
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
    Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [PageController::class, 'terms'])->name('terms');
    Route::get('/cv-tips', [PageController::class, 'cvTips'])->name('cv-tips');
    Route::get('/foreign-safety', [PageController::class, 'foreignSafety'])->name('foreign-safety');
    Route::get('/help-center', [PageController::class, 'helpCenter'])->name('help-center');
    Route::get('/help-center/article/{category}/{index}', [PageController::class, 'getHelpArticle'])->name('help.article');
    Route::post('/help-center/feedback', [PageController::class, 'submitHelpfulFeedback'])->name('help.feedback');
    Route::post('/help-center/contact', [PageController::class, 'helpContact'])->name('help.contact');
});

// ────────────────────────────────────────────────
// 2. Breeze Authentication Routes
// ────────────────────────────────────────────────

require __DIR__.'/auth.php';

// ────────────────────────────────────────────────
// 3. Email Verification Routes
// ────────────────────────────────────────────────

require __DIR__.'/verification.php';

// ────────────────────────────────────────────────
// 4. Protected Routes (must be logged in + email verified)
// ────────────────────────────────────────────────
    // ────────────────────────────────────────────────
// Protected Routes (must be logged in)
// ────────────────────────────────────────────────

// Basic auth-required routes (edit profile, etc. — no verification needed to access edit form)
Route::middleware('auth')->group(function () {
    // Main profile edit page
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    
    // Basic info update (form submit)
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Photo routes
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'removePhoto'])->name('profile.photo.remove');
    
    // Headline & Summary (AJAX)
    Route::post('/profile/headline', [ProfileController::class, 'updateHeadline'])->name('profile.headline.update');
    Route::post('/profile/summary', [ProfileController::class, 'updateSummary'])->name('profile.summary.update');
    
    // Skills (AJAX)
    Route::post('/profile/skills', [ProfileController::class, 'updateSkills'])->name('profile.skills.update');
    
    // Resume routes
    Route::post('/profile/resume', [ProfileController::class, 'uploadResume'])->name('profile.resume.upload');
    Route::delete('/profile/resume', [ProfileController::class, 'deleteResume'])->name('profile.resume.delete');
    
    // Job Preferences (AJAX)
    Route::post('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences.update');
    
    // Profile completion (AJAX)
    Route::get('/profile/completion', [ProfileController::class, 'getCompletionData'])->name('profile.completion');
    
    // Education CRUD
    Route::post('/education', [ProfileController::class, 'storeEducation'])->name('education.store');
    Route::get('/education/{id}', [ProfileController::class, 'getEducation'])->name('education.get');
    Route::put('/education/{id}', [ProfileController::class, 'updateEducation'])->name('education.update');
    Route::delete('/education/{id}', [ProfileController::class, 'destroyEducation'])->name('education.destroy');
    
    // Experience CRUD
    Route::post('/experience', [ProfileController::class, 'storeExperience'])->name('experience.store');
    Route::get('/experience/{id}', [ProfileController::class, 'getExperience'])->name('experience.get');
    Route::put('/experience/{id}', [ProfileController::class, 'updateExperience'])->name('experience.update');
    Route::delete('/experience/{id}', [ProfileController::class, 'destroyExperience'])->name('experience.destroy');
    // Password routes
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
});

// Full verification + account active required for actions that modify data
Route::middleware(['auth', 'verified', 'account.active'])->group(function () {
    // Show profile (can be public or restricted later)
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

  

    // Password routes
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    
    // ────────────────────────────────────────────────
    // 5. Role-based Dashboards & Features
    // ────────────────────────────────────────────────

    // Job Seeker Area
    Route::middleware('role:job_seeker')->group(function () {
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/', [JobSeekerDashboardController::class, 'index'])->name('jobseeker');
            
            // Future: applications, saved jobs, profile completion, etc.
            // Route::get('/applications', ...)->name('applications');
            
        });
        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
         Route::get('/saved-jobs', [SavedJobController::class, 'index'])
            ->name('saved.jobs');
    });

    // Employer Area
    Route::middleware(['role:employer'])->prefix('employer')->name('employer.')->group(function () {
        // Dashboard
        // Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/company/create', [CompanyController::class, 'create'])
            ->name('company.create');

        Route::post('/company/store', [CompanyController::class, 'store'])
            ->name('company.store');
        // Job Management
        // Route::resource('jobs', EmployerJobController::class)->except(['show']);

        // Company Management
        // Route::get('/company/create', [CompanyController::class, 'create'])->name('company.create');
        // Route::post('/company', [CompanyController::class, 'store'])->name('company.store');

        // Applicants
        // Route::get('/applicants', [EmployerApplicantController::class, 'index'])->name('applicants.index');
    });

    // Admin / Super Admin Area
    Route::middleware(['role:admin|super_admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        // Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Job Moderation
        // Route::get('/jobs/pending', [AdminJobController::class, 'pending'])->name('jobs.pending');
        // Route::post('/jobs/{job}/approve', [AdminJobController::class, 'approve'])->name('jobs.approve');
        // Route::post('/jobs/{job}/reject', [AdminJobController::class, 'reject'])->name('jobs.reject');

        // User & Company Management (future)
    });
});

// ────────────────────────────────────────────────
// Optional: Fallback / Catch-all
// ────────────────────────────────────────────────
// If user logs in but has no role → redirect to profile or assign default
// This can be handled in middleware or Login event listener later