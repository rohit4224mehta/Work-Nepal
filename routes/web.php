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

// Job Seeker Controllers
use App\Http\Controllers\JobSeeker\SavedJobController;
use App\Http\Controllers\Dashboard\JobSeekerDashboardController;

// Employer Controllers
use App\Http\Controllers\Employer\CompanyController as EmployerCompanyController;
use App\Http\Controllers\Employer\JobController as EmployerJobController;
use App\Http\Controllers\Employer\ApplicantController as EmployerApplicantController;
use App\Http\Controllers\Employer\DashboardController as EmployerDashboardController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ContentController as AdminContentController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController; // Add this

// Notification Controller
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes - WorkNepal Job Platform
|--------------------------------------------------------------------------
|
| 1. Public routes (guest access)
| 2. Authentication routes (login, register, password reset)
| 3. Email verification routes
| 4. Protected routes (login required)
| 5. Role-based dashboards & features
|
*/

// ==================== 1. PUBLIC ROUTES ====================

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Jobs (public search & view)
Route::prefix('jobs')->name('jobs.')->group(function () {
    Route::get('/', [JobController::class, 'index'])->name('index');
    Route::get('/{job:slug}', [JobController::class, 'show'])->name('show');
    Route::get('/suggestions', [JobController::class, 'suggestions'])->name('suggestions');
    Route::post('/{job}/quick-apply', [JobController::class, 'quickApply'])->name('quick-apply');
    Route::post('/{job}/toggle-save', [JobController::class, 'toggleSave'])->name('toggle-save');
});

// Companies (public profiles)
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
    
    // Job Seeker Dashboard
    Route::get('/dashboard', [JobSeekerDashboardController::class, 'index'])->name('dashboard.jobseeker');
    Route::get('/dashboard/refresh', [JobSeekerDashboardController::class, 'refreshData'])->name('dashboard.refresh');
    
    // Applications
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::post('/jobs/{jobId}/apply', [ApplicationController::class, 'store'])->name('jobs.apply');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::delete('/applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
    
    // Saved Jobs
    Route::prefix('saved-jobs')->name('saved.')->group(function () {
        Route::get('/', [SavedJobController::class, 'index'])->name('jobs');
        Route::post('/jobs/{jobId}/save', [SavedJobController::class, 'save'])->name('jobs.save');
        Route::delete('/jobs/{jobId}/unsave', [SavedJobController::class, 'unsave'])->name('jobs.unsave');
    });

    // ==================== 5. EMPLOYER ROUTES ====================
    
    Route::prefix('employer')->name('employer.')->group(function () {
        
        // Multi-step company creation (accessible to all authenticated users)
        Route::get('/company/create', [EmployerCompanyController::class, 'create'])->name('company.create');
        Route::post('/company/step1', [EmployerCompanyController::class, 'storeStep1'])->name('company.store.step1');
        Route::get('/company/details', [EmployerCompanyController::class, 'details'])->name('company.details');
        Route::post('/company/step2', [EmployerCompanyController::class, 'storeStep2'])->name('company.store.step2');
        Route::get('/company/branding', [EmployerCompanyController::class, 'branding'])->name('company.branding');
        Route::post('/company/step3', [EmployerCompanyController::class, 'storeStep3'])->name('company.store.step3');
        Route::get('/company/review', [EmployerCompanyController::class, 'review'])->name('company.review');
        Route::post('/company/final', [EmployerCompanyController::class, 'storeFinal'])->name('company.store.final');
        Route::get('/company/{company}/success', [EmployerCompanyController::class, 'success'])->name('company.success');
        Route::get('/company/{company}/preview', [EmployerCompanyController::class, 'preview'])->name('company.preview');
        
        // Aliases for backward compatibility
        Route::get('/post-job', fn() => redirect()->route('employer.jobs.create'))->name('post.job');
        Route::get('/applicants', fn() => redirect()->route('employer.applicants.index'))->name('applicants');

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
            Route::post('/company/{company}/team/{user}/toggle', [EmployerCompanyController::class, 'toggleTeamMemberStatus'])->name('company.team.toggle');
            Route::post('/company/{company}/team/{user}/role', [EmployerCompanyController::class, 'updateTeamMemberRole'])->name('company.team.role');
        });
    });

    // ==================== 6. ADMIN ROUTES ====================
    
    Route::middleware(['role:admin|super_admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/export', [AdminDashboardController::class, 'export'])->name('dashboard.export');
        Route::get('/dashboard/refresh', [AdminDashboardController::class, 'refresh'])->name('dashboard.refresh');
        
        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::get('/job-seekers', [AdminUserController::class, 'jobSeekers'])->name('job-seekers');
            Route::get('/employers', [AdminUserController::class, 'employers'])->name('employers');
            Route::get('/export', [AdminUserController::class, 'export'])->name('export');
            Route::post('/bulk-action', [AdminUserController::class, 'bulkAction'])->name('bulk-action');
            Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
            Route::post('/{user}/suspend', [AdminUserController::class, 'suspend'])->name('suspend');
            Route::post('/{user}/activate', [AdminUserController::class, 'activate'])->name('activate');
            Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/impersonate', [AdminUserController::class, 'impersonate'])->name('impersonate')->middleware('role:super_admin');
            Route::post('/stop-impersonate', [AdminUserController::class, 'stopImpersonate'])->name('stop-impersonate')->middleware('role:super_admin');
            Route::post('/{user}/send-password-reset', [AdminUserController::class, 'sendPasswordReset'])->name('send-password-reset');
            Route::get('/{user}/activity', [AdminUserController::class, 'activityLog'])->name('activity');
        });
        
        // Company Management
        Route::prefix('companies')->name('companies.')->group(function () {
            Route::get('/', [AdminCompanyController::class, 'index'])->name('index');
            Route::get('/pending', [AdminCompanyController::class, 'pending'])->name('pending');
            Route::get('/verified', [AdminCompanyController::class, 'verified'])->name('verified');
            Route::get('/export', [AdminCompanyController::class, 'export'])->name('export');
            Route::post('/bulk-action', [AdminCompanyController::class, 'bulkAction'])->name('bulk-action');
            Route::get('/{company}', [AdminCompanyController::class, 'show'])->name('show');
            Route::get('/{company}/insights', [AdminCompanyController::class, 'insights'])->name('insights');
            Route::post('/{company}/verify', [AdminCompanyController::class, 'verify'])->name('verify');
            Route::post('/{company}/reject', [AdminCompanyController::class, 'reject'])->name('reject');
            Route::post('/{company}/suspend', [AdminCompanyController::class, 'suspend'])->name('suspend');
            Route::post('/{company}/activate', [AdminCompanyController::class, 'activate'])->name('activate');
            Route::delete('/{company}', [AdminCompanyController::class, 'destroy'])->name('destroy');
        });
        
        // Job Management
        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/', [AdminJobController::class, 'index'])->name('index');
            Route::get('/pending', [AdminJobController::class, 'pending'])->name('pending');
            Route::get('/featured', [AdminJobController::class, 'featured'])->name('featured');
            Route::get('/export', [AdminJobController::class, 'export'])->name('export');
            Route::post('/bulk-action', [AdminJobController::class, 'bulkAction'])->name('bulk-action');
            Route::get('/{job}', [AdminJobController::class, 'show'])->name('show');
            Route::get('/{job}/insights', [AdminJobController::class, 'insights'])->name('insights');
            Route::post('/{job}/approve', [AdminJobController::class, 'approve'])->name('approve');
            Route::post('/{job}/reject', [AdminJobController::class, 'reject'])->name('reject');
            Route::post('/{job}/feature', [AdminJobController::class, 'feature'])->name('feature');
            Route::post('/{job}/toggle-status', [AdminJobController::class, 'toggleStatus'])->name('toggle-status');
            Route::delete('/{job}', [AdminJobController::class, 'destroy'])->name('destroy');
        });
        
        // Applications Management
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [AdminApplicationController::class, 'index'])->name('index');
            Route::get('/stats', [AdminApplicationController::class, 'getStats'])->name('stats');
            Route::get('/export', [AdminApplicationController::class, 'export'])->name('export');
            Route::post('/bulk-update', [AdminApplicationController::class, 'bulkUpdate'])->name('bulk-update');
            Route::delete('/bulk-delete', [AdminApplicationController::class, 'bulkDelete'])->name('bulk-delete');
            Route::get('/job/{job}', [AdminApplicationController::class, 'jobApplications'])->name('job');
            Route::get('/user/{user}', [AdminApplicationController::class, 'userApplications'])->name('user');
            Route::get('/{application}', [AdminApplicationController::class, 'show'])->name('show');
            Route::post('/{application}/status', [AdminApplicationController::class, 'updateStatus'])->name('status');
            Route::post('/{application}/feedback', [AdminApplicationController::class, 'addFeedback'])->name('feedback');
            Route::delete('/{application}', [AdminApplicationController::class, 'destroy'])->name('destroy');
        });
        
        // Content Moderation
        Route::prefix('content')->name('content.')->group(function () {
            
            // Testimonials
            Route::prefix('testimonials')->name('testimonials.')->group(function () {
                Route::get('/', [AdminContentController::class, 'testimonials'])->name('index');
                Route::get('/export', [AdminContentController::class, 'exportTestimonials'])->name('export');
                Route::post('/bulk', [AdminContentController::class, 'bulkTestimonials'])->name('bulk');
                Route::post('/{testimonial}/approve', [AdminContentController::class, 'approveTestimonial'])->name('approve');
                Route::post('/{testimonial}/reject', [AdminContentController::class, 'rejectTestimonial'])->name('reject');
                Route::post('/{testimonial}/toggle-featured', [AdminContentController::class, 'toggleFeatured'])->name('toggle-featured');
                Route::delete('/{testimonial}', [AdminContentController::class, 'deleteTestimonial'])->name('delete');
            });
            
            // Pages (CMS)
            Route::prefix('pages')->name('pages.')->group(function () {
                Route::get('/', [AdminContentController::class, 'pages'])->name('index');
                Route::get('/create', [AdminContentController::class, 'createPage'])->name('create');
                Route::post('/store', [AdminContentController::class, 'storePage'])->name('store');
                Route::get('/{page}/edit', [AdminContentController::class, 'editPage'])->name('edit');
                Route::put('/{page}', [AdminContentController::class, 'updatePage'])->name('update');
                Route::post('/{page}/toggle-status', [AdminContentController::class, 'togglePageStatus'])->name('toggle-status');
                Route::delete('/{page}', [AdminContentController::class, 'deletePage'])->name('delete');
            });
            
            // Stats
            Route::get('/stats', [AdminContentController::class, 'getStats'])->name('stats');
        });
        
        // Reports & Abuse Handling
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [AdminReportController::class, 'index'])->name('index');
            Route::get('/export', [AdminReportController::class, 'export'])->name('export');
            Route::get('/stats', [AdminReportController::class, 'getStats'])->name('stats');
            Route::post('/bulk', [AdminReportController::class, 'bulkAction'])->name('bulk');
            Route::get('/{report}', [AdminReportController::class, 'show'])->name('show');
            Route::post('/{report}/status', [AdminReportController::class, 'updateStatus'])->name('status');
            Route::post('/{report}/assign', [AdminReportController::class, 'assign'])->name('assign');
            Route::post('/{report}/action', [AdminReportController::class, 'takeAction'])->name('action');
            Route::delete('/{report}', [AdminReportController::class, 'destroy'])->name('destroy');
        });
        
        // System Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [AdminSettingController::class, 'index'])->name('index');
            Route::post('/', [AdminSettingController::class, 'update'])->name('update');
            Route::post('/update-env', [AdminSettingController::class, 'updateEnv'])->name('update-env');
            Route::post('/test-email', [AdminSettingController::class, 'testEmail'])->name('test-email');
            Route::post('/clear-cache', [AdminSettingController::class, 'clearCache'])->name('clear-cache');
            Route::get('/reset', [AdminSettingController::class, 'resetDefaults'])->name('reset');
            Route::get('/export', [AdminSettingController::class, 'export'])->name('export');
            Route::post('/import', [AdminSettingController::class, 'import'])->name('import');
        });
        
        // Activity Logs
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', [AdminActivityLogController::class, 'index'])->name('index');
            Route::get('/stats', [AdminActivityLogController::class, 'stats'])->name('stats');
            Route::get('/export', [AdminActivityLogController::class, 'export'])->name('export');
            Route::get('/stream', [AdminActivityLogController::class, 'stream'])->name('stream');
            Route::post('/clear', [AdminActivityLogController::class, 'clear'])->name('clear');
            Route::get('/{log}', [AdminActivityLogController::class, 'show'])->name('show');
        });
        
        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

        // ==================== ADMIN PROFILE ROUTES ====================
        // These are now correctly placed inside the admin middleware group
        
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [AdminProfileController::class, 'show'])->name('show');
            Route::get('/edit', [AdminProfileController::class, 'edit'])->name('edit');
            Route::put('/', [AdminProfileController::class, 'update'])->name('update');
            Route::delete('/remove-photo', [AdminProfileController::class, 'removePhoto'])->name('remove-photo');
            Route::get('/password', [AdminProfileController::class, 'password'])->name('password');
            Route::put('/password', [AdminProfileController::class, 'updatePassword'])->name('password.update');
            Route::get('/activity', [AdminProfileController::class, 'activityLog'])->name('activity');
        });
    });
});

// ==================== 7. API ROUTES (AJAX) ====================

Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    Route::post('/jobs/{jobId}/save', [SavedJobController::class, 'save'])->name('jobs.save');
    Route::delete('/jobs/{jobId}/unsave', [SavedJobController::class, 'unsave'])->name('jobs.unsave');
    Route::post('/jobs/{job}/quick-apply', [JobController::class, 'quickApply'])->name('jobs.quick-apply');
    Route::post('/jobs/{job}/toggle-save', [JobController::class, 'toggleSave'])->name('jobs.toggle-save');
    Route::get('/jobs/suggestions', [JobController::class, 'suggestions'])->name('jobs.suggestions');
    Route::get('/companies/suggestions', [CompaniesController::class, 'suggestions'])->name('companies.suggestions');
    Route::get('/dashboard/refresh', [JobSeekerDashboardController::class, 'refreshData'])->name('dashboard.refresh');
});

// ==================== 8. FALLBACK ROUTE ====================

Route::fallback(function () {
    return view('errors.404');
});