<?php

use Illuminate\Support\Facades\Route;

// Public Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;

// Job Seeker Controllers
use App\Http\Controllers\JobSeeker\SavedJobController;
use App\Http\Controllers\Dashboard\JobSeekerDashboardController;

// Employer Controllers
use App\Http\Controllers\Employer\CompanyController as EmployerCompanyController;
use App\Http\Controllers\Employer\JobController as EmployerJobController;
use App\Http\Controllers\Employer\ApplicantController as EmployerApplicantController;
use App\Http\Controllers\Employer\DashboardController as EmployerDashboardController;

// Notification Controllers
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes - WorkNepal Job Platform
|--------------------------------------------------------------------------
*/

// ==================== 1. PUBLIC ROUTES ====================

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Jobs Routes
Route::prefix('jobs')->name('jobs.')->group(function () {
    Route::get('/', [JobController::class, 'index'])->name('index');
    Route::get('/suggestions', [JobController::class, 'suggestions'])->name('suggestions');
    Route::get('/{job:slug}', [JobController::class, 'show'])->name('show');
    Route::post('/report', [JobController::class, 'report'])->name('report');
    
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::post('/{job:slug}/apply', [JobController::class, 'apply'])
            ->name('apply')
            ->middleware('throttle:10,1');
        Route::post('/{job:slug}/toggle-save', [JobController::class, 'toggleSave'])
            ->name('toggle-save')
            ->middleware('throttle:20,1');
    });
});

// Companies Routes
Route::prefix('companies')->name('companies.')->group(function () {
    Route::get('/', [CompaniesController::class, 'index'])->name('index');
    Route::get('/search/suggestions', [CompaniesController::class, 'suggestions'])->name('suggestions');
    Route::get('/industry/{industry}', [CompaniesController::class, 'byIndustry'])->name('industry');
    Route::get('/featured', [CompaniesController::class, 'featured'])->name('featured');
    Route::get('/location/{location}', [CompaniesController::class, 'byLocation'])->name('location');
    Route::get('/{slug}', [CompaniesController::class, 'show'])->name('show');
    Route::post('/{company}/toggle-follow', [CompaniesController::class, 'toggleFollow'])->name('toggle-follow');
    Route::post('/{company}/report', [CompaniesController::class, 'report'])->name('report');
});

// Static Pages
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

// ==================== 2. AUTHENTICATION ROUTES ====================

require __DIR__.'/auth.php';
require __DIR__.'/verification.php';

// ==================== 3. PROTECTED ROUTES (Logged in users) ====================

Route::middleware(['auth'])->group(function () {
    
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/photo', [ProfileController::class, 'updatePhoto'])->name('photo.update');
        Route::delete('/photo', [ProfileController::class, 'removePhoto'])->name('photo.remove');
        Route::post('/headline', [ProfileController::class, 'updateHeadline'])->name('headline.update');
        Route::post('/summary', [ProfileController::class, 'updateSummary'])->name('summary.update');
        Route::post('/skills', [ProfileController::class, 'updateSkills'])->name('skills.update');
        Route::post('/resume', [ProfileController::class, 'uploadResume'])->name('resume.upload');
        Route::delete('/resume', [ProfileController::class, 'deleteResume'])->name('resume.delete');
        Route::post('/preferences', [ProfileController::class, 'updatePreferences'])->name('preferences.update');
        Route::get('/completion', [ProfileController::class, 'getCompletionData'])->name('completion');
        Route::get('/password', [ProfileController::class, 'password'])->name('password');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::get('/{user}', [ProfileController::class, 'show'])->name('show');
    });

    // Education Routes
    Route::prefix('education')->name('education.')->group(function () {
        Route::get('/create', [ProfileController::class, 'createEducation'])->name('create');
        Route::post('/', [ProfileController::class, 'storeEducation'])->name('store');
        Route::get('/{id}/edit', [ProfileController::class, 'editEducation'])->name('edit');
        Route::put('/{id}', [ProfileController::class, 'updateEducation'])->name('update');
        Route::delete('/{id}', [ProfileController::class, 'destroyEducation'])->name('destroy');
    });

    // Experience Routes
    Route::prefix('experience')->name('experience.')->group(function () {
        Route::get('/create', [ProfileController::class, 'createExperience'])->name('create');
        Route::post('/', [ProfileController::class, 'storeExperience'])->name('store');
        Route::get('/{id}/edit', [ProfileController::class, 'editExperience'])->name('edit');
        Route::put('/{id}', [ProfileController::class, 'updateExperience'])->name('update');
        Route::delete('/{id}', [ProfileController::class, 'destroyExperience'])->name('destroy');
    });

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/profile', [SettingsController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [SettingsController::class, 'updatePassword'])->name('password.update');
        Route::put('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications.update');
        Route::put('/privacy', [SettingsController::class, 'updatePrivacy'])->name('privacy.update');
        Route::get('/delete/confirm', [SettingsController::class, 'confirmDelete'])->name('delete.confirm');
        Route::delete('/account', [SettingsController::class, 'deleteAccount'])->name('account.delete');
    });
});

// ==================== 4. VERIFIED + ACTIVE ACCOUNT ROUTES ====================

Route::middleware(['auth', 'verified', 'account.active'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/info', [DashboardController::class, 'getDashboardInfo'])->name('dashboard.info');
    Route::get('/dashboard/quick-stats', [DashboardController::class, 'quickStats'])->name('dashboard.quick-stats');
    Route::get('/dashboard/available', [DashboardController::class, 'getAvailableDashboards'])->name('dashboard.available');
    Route::post('/dashboard/switch', [DashboardController::class, 'switchDashboard'])->name('dashboard.switch');
    
    // Job Seeker Dashboard
    Route::get('/dashboard/jobseeker', [JobSeekerDashboardController::class, 'index'])->name('dashboard.jobseeker');
    Route::get('/dashboard/jobseeker/refresh', [JobSeekerDashboardController::class, 'refreshData'])->name('dashboard.jobseeker.refresh');
    
    // Applications
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::post('/jobs/{jobId}/apply', [ApplicationController::class, 'store'])->name('jobs.apply');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::delete('/applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
    
    // Saved Jobs
    Route::prefix('saved-jobs')->name('saved-jobs.')->group(function () {
        Route::get('/', [SavedJobController::class, 'index'])->name('index');
        Route::post('/{jobId}', [SavedJobController::class, 'save'])->name('save');
        Route::delete('/{jobId}', [SavedJobController::class, 'unsave'])->name('unsave');
    });

    // ==================== EMPLOYER ROUTES ====================
    Route::prefix('employer')->name('employer.')->group(function () {
        
        // Company Management
        Route::prefix('company')->name('company.')->group(function () {
            Route::get('/create', [EmployerCompanyController::class, 'create'])->name('create');
            Route::post('/step1', [EmployerCompanyController::class, 'storeStep1'])->name('store.step1');
            Route::get('/details', [EmployerCompanyController::class, 'details'])->name('details');
            Route::post('/step2', [EmployerCompanyController::class, 'storeStep2'])->name('store.step2');
            Route::get('/branding', [EmployerCompanyController::class, 'branding'])->name('branding');
            Route::post('/step3', [EmployerCompanyController::class, 'storeStep3'])->name('store.step3');
            Route::get('/review', [EmployerCompanyController::class, 'review'])->name('review');
            Route::post('/final', [EmployerCompanyController::class, 'storeFinal'])->name('store.final');
            Route::get('/{company}/success', [EmployerCompanyController::class, 'success'])->name('success');
            Route::get('/{company}/preview', [EmployerCompanyController::class, 'preview'])->name('preview');
            Route::get('/{company}/edit', [EmployerCompanyController::class, 'edit'])->name('edit');
            Route::put('/{company}', [EmployerCompanyController::class, 'update'])->name('update');
            Route::get('/{company}/team', [EmployerCompanyController::class, 'team'])->name('team');
            Route::post('/{company}/team/add', [EmployerCompanyController::class, 'addTeamMember'])->name('team.add');
            Route::delete('/{company}/team/{user}', [EmployerCompanyController::class, 'removeTeamMember'])->name('team.remove');
            Route::post('/{company}/team/{user}/toggle', [EmployerCompanyController::class, 'toggleTeamMemberStatus'])->name('team.toggle');
            Route::post('/{company}/team/{user}/role', [EmployerCompanyController::class, 'updateTeamMemberRole'])->name('team.role');
        });
        
        // Aliases
        Route::get('/post-job', fn() => redirect()->route('employer.jobs.create'))->name('post.job');
        Route::get('/applicants', fn() => redirect()->route('employer.applicants.index'))->name('applicants');

        // Employer Dashboard
        Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');
        
        // Employer-Only Routes
        Route::middleware(['role:employer'])->group(function () {
            Route::resource('jobs', EmployerJobController::class)->except(['show']);
            Route::get('/jobs/{job}/applications', [EmployerJobController::class, 'applications'])->name('jobs.applications');
            Route::get('/applicants', [EmployerApplicantController::class, 'index'])->name('applicants.index');
            Route::get('/applicants/{application}', [EmployerApplicantController::class, 'show'])->name('applicants.show');
            Route::patch('/applicants/{application}/status', [EmployerApplicantController::class, 'updateStatus'])->name('applicants.status');
        });
    });
    
    // ==================== USER NOTIFICATION ROUTES ====================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/recent', [NotificationController::class, 'getRecent'])->name('recent');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/clear-all', [NotificationController::class, 'clearAll'])->name('clear-all');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    // ==================== ADMIN ROUTES (Separate File) ====================
    require __DIR__.'/admin.php';
});

// ==================== API ROUTES ====================

Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    Route::prefix('saved-jobs')->name('saved-jobs.')->group(function () {
        Route::post('/{jobId}', [SavedJobController::class, 'save'])->name('save');
        Route::delete('/{jobId}', [SavedJobController::class, 'unsave'])->name('unsave');
    });
    
    Route::post('/jobs/{job}/quick-apply', [JobController::class, 'quickApply'])->name('jobs.quick-apply');
    Route::post('/jobs/{job}/toggle-save', [JobController::class, 'toggleSave'])->name('jobs.toggle-save');
    Route::get('/jobs/suggestions', [JobController::class, 'suggestions'])->name('jobs.suggestions');
    Route::get('/companies/suggestions', [CompaniesController::class, 'suggestions'])->name('companies.suggestions');
    Route::get('/dashboard/refresh', [JobSeekerDashboardController::class, 'refreshData'])->name('dashboard.refresh');
});

// ==================== FALLBACK ROUTE ====================

Route::fallback(function () {
    return view('errors.404');
});