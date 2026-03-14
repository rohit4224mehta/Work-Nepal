<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Dashboard\JobSeekerDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobSeeker\SavedJobController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Employer\CompanyController as EmployerCompanyController;
use App\Http\Controllers\Employer\JobController as EmployerJobController;
use App\Http\Controllers\Employer\ApplicantController as EmployerApplicantController;
use App\Http\Controllers\Employer\DashboardController as EmployerDashboardController;
use App\Http\Controllers\NotificationController;


use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
// use App\Http\Controllers\Admin\CompanyController;
// use App\Http\Controllers\Admin\JobController;
// use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TestimonialController;
// use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LogController;
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
    Route::get('/', [CompaniesController::class, 'index'])->name('index');
    Route::get('/search/suggestions', [CompaniesController::class, 'suggestions'])->name('suggestions');
    Route::get('/industry/{industry}', [CompaniesController::class, 'byIndustry'])->name('industry');
    Route::get('/{slug}', [CompaniesController::class, 'show'])->name('show');
});

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
// 4. Protected Routes (must be logged in)
// ────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    
    // Profile Management
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Photo routes
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'removePhoto'])->name('profile.photo.remove');
    
    // Headline & Summary
    Route::post('/profile/headline', [ProfileController::class, 'updateHeadline'])->name('profile.headline.update');
    Route::post('/profile/summary', [ProfileController::class, 'updateSummary'])->name('profile.summary.update');
    
    // Skills
    Route::post('/profile/skills', [ProfileController::class, 'updateSkills'])->name('profile.skills.update');
    
    // Resume routes
    Route::post('/profile/resume', [ProfileController::class, 'uploadResume'])->name('profile.resume.upload');
    Route::delete('/profile/resume', [ProfileController::class, 'deleteResume'])->name('profile.resume.delete');
    
    // Job Preferences
    Route::post('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences.update');
    
    // Profile completion (optional AJAX)
    Route::get('/profile/completion', [ProfileController::class, 'getCompletionData'])->name('profile.completion');
    
    // Education routes
    Route::get('/education/create', [ProfileController::class, 'createEducation'])->name('education.create');
    Route::post('/education', [ProfileController::class, 'storeEducation'])->name('education.store');
    Route::get('/education/{id}/edit', [ProfileController::class, 'editEducation'])->name('education.edit');
    Route::put('/education/{id}', [ProfileController::class, 'updateEducation'])->name('education.update');
    Route::delete('/education/{id}', [ProfileController::class, 'destroyEducation'])->name('education.destroy');
    
    // Experience routes
    Route::get('/experience/create', [ProfileController::class, 'createExperience'])->name('experience.create');
    Route::post('/experience', [ProfileController::class, 'storeExperience'])->name('experience.store');
    Route::get('/experience/{id}/edit', [ProfileController::class, 'editExperience'])->name('experience.edit');
    Route::put('/experience/{id}', [ProfileController::class, 'updateExperience'])->name('experience.update');
    Route::delete('/experience/{id}', [ProfileController::class, 'destroyExperience'])->name('experience.destroy');
    
    // Password routes
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // Public profile view (anyone logged in can view)
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
});

// Settings Routes
Route::middleware(['auth'])->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::put('/profile', [SettingsController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [SettingsController::class, 'updatePassword'])->name('password.update');
    Route::put('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications.update');
    Route::put('/privacy', [SettingsController::class, 'updatePrivacy'])->name('privacy.update');
    Route::get('/delete/confirm', [SettingsController::class, 'confirmDelete'])->name('delete.confirm');
    Route::delete('/account', [SettingsController::class, 'deleteAccount'])->name('account.delete');
});

// ────────────────────────────────────────────────
// 5. Full Verification Required Routes
// ────────────────────────────────────────────────

Route::middleware(['auth', 'verified', 'account.active'])->group(function () {
    
    // Job Seeker Routes
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [JobSeekerDashboardController::class, 'index'])->name('jobseeker');
    });
    
    // Applications
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::post('/jobs/{jobId}/apply', [ApplicationController::class, 'store'])->name('jobs.apply');
    
    // Saved Jobs
    Route::prefix('saved-jobs')->name('saved.')->group(function () {
        Route::get('/', [SavedJobController::class, 'index'])->name('jobs');
        Route::post('/jobs/{jobId}/save', [SavedJobController::class, 'save'])->name('jobs.save');
        Route::delete('/jobs/{jobId}/unsave', [SavedJobController::class, 'unsave'])->name('jobs.unsave');
    });

    // ────────────────────────────────────────────────
    // 6. Employer Routes (multi-step company creation)
    // ────────────────────────────────────────────────
    
    Route::prefix('employer')->name('employer.')->group(function () {
        
        // Multi-step company creation (accessible to job_seekers AND employers)
        Route::get('/company/create', [EmployerCompanyController::class, 'create'])->name('company.create');
        Route::post('/company/step1', [EmployerCompanyController::class, 'storeStep1'])->name('company.store.step1');
        
        Route::get('/company/details', [EmployerCompanyController::class, 'details'])->name('company.details');
        Route::post('/company/step2', [EmployerCompanyController::class, 'storeStep2'])->name('company.store.step2');
        
        Route::get('/company/branding', [EmployerCompanyController::class, 'branding'])->name('company.branding');
        Route::post('/company/step3', [EmployerCompanyController::class, 'storeStep3'])->name('company.store.step3');
        
        Route::get('/company/review', [EmployerCompanyController::class, 'review'])->name('company.review');
        Route::post('/company/final', [EmployerCompanyController::class, 'storeFinal'])->name('company.store.final');
        
        Route::get('/company/{company}/success', [EmployerCompanyController::class, 'success'])->name('company.success');
        
        Route::get('/company/{company}/preview', [EmployerCompanyController::class, 'preview'])
        ->name('company.preview');
        Route::get('/post-job', function() {
        return redirect()->route('employer.jobs.create');
    })->name('post.job');
    Route::get('/applicants', function() {
        return redirect()->route('employer.applicants.index');
    })->name('applicants');


        // Employer Dashboard (only employers can access)
        Route::middleware(['role:employer'])->group(function () {
            
            Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');
            
            // Job Management
            Route::resource('jobs', EmployerJobController::class)->except(['show']);
            Route::get('/jobs/{job}/applications', [EmployerJobController::class, 'applications'])->name('jobs.applications');
            
            // Applicants Management
            Route::get('/applicants', [EmployerApplicantController::class, 'index'])->name('applicants.index');
            Route::get('/applicants/{application}', [EmployerApplicantController::class, 'show'])->name('applicants.show');
            Route::patch('/applicants/{application}/status', [EmployerApplicantController::class, 'updateStatus'])->name('applicants.status');
            
            // Team Management
            Route::get('/company/{company}/team', [EmployerCompanyController::class, 'team'])->name('company.team');
            Route::post('/company/{company}/team/add', [EmployerCompanyController::class, 'addTeamMember'])->name('company.team.add');
            Route::delete('/company/{company}/team/{user}', [EmployerCompanyController::class, 'removeTeamMember'])->name('company.team.remove');

            
        });
    });

    // ────────────────────────────────────────────────
    // 7. Admin / Super Admin Routes
    // ────────────────────────────────────────────────
    
    Route::middleware(['role:admin|super_admin'])->prefix('admin')->name('admin.')->group(function () {
          
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');
    Route::get('/dashboard/refresh', [DashboardController::class, 'refresh'])->name('dashboard.refresh');
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/job-seekers', [UserController::class, 'jobSeekers'])->name('job-seekers');
        Route::get('/employers', [UserController::class, 'employers'])->name('employers');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::post('/{user}/suspend', [UserController::class, 'suspend'])->name('suspend');
        Route::post('/{user}/activate', [UserController::class, 'activate'])->name('activate');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
    
    // Company Management
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('index');
        Route::get('/pending', [CompanyController::class, 'pending'])->name('pending');
        Route::get('/verified', [CompanyController::class, 'verified'])->name('verified');
        Route::get('/{company}', [CompanyController::class, 'show'])->name('show');
        Route::post('/{company}/verify', [CompanyController::class, 'verify'])->name('verify');
        Route::post('/{company}/reject', [CompanyController::class, 'reject'])->name('reject');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('destroy');
    });
    
    // Job Management
    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::get('/', [JobController::class, 'index'])->name('index');
        Route::get('/pending', [JobController::class, 'pending'])->name('pending');
        Route::get('/featured', [JobController::class, 'featured'])->name('featured');
        Route::get('/{job}', [JobController::class, 'show'])->name('show');
        Route::post('/{job}/approve', [JobController::class, 'approve'])->name('approve');
        Route::post('/{job}/reject', [JobController::class, 'reject'])->name('reject');
        Route::post('/{job}/feature', [JobController::class, 'feature'])->name('feature');
        Route::delete('/{job}', [JobController::class, 'destroy'])->name('destroy');
    });
    
    // Applications
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    
    // Testimonials
    Route::resource('testimonials', TestimonialController::class);
    
    // Pages
    Route::resource('pages', PageController::class);
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    
    // Logs
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('/logs/{log}', [LogController::class, 'show'])->name('logs.show');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});
});

// ────────────────────────────────────────────────
// 9. API-like Routes for AJAX (optional)
// ────────────────────────────────────────────────

Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    Route::post('/jobs/{jobId}/save', [SavedJobController::class, 'save'])->name('jobs.save');
    Route::delete('/jobs/{jobId}/unsave', [SavedJobController::class, 'unsave'])->name('jobs.unsave');
    Route::get('/dashboard/refresh', [JobSeekerDashboardController::class, 'refreshData'])->name('dashboard.refresh');
});