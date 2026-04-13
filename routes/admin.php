<?php

use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;

/*
|--------------------------------------------------------------------------
| Admin Routes - WorkNepal
|--------------------------------------------------------------------------
*/

Route::middleware(['role:admin|super_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // ==================== DASHBOARD ====================
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export', [AdminDashboardController::class, 'export'])->name('dashboard.export');
    Route::get('/dashboard/refresh', [AdminDashboardController::class, 'refresh'])->name('dashboard.refresh');
    
    // ==================== USER MANAGEMENT ====================
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
    
    // ==================== COMPANY MANAGEMENT ====================
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
    
    // ==================== JOB MANAGEMENT ====================
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
    
    // ==================== APPLICATION MANAGEMENT ====================
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
    
    // ==================== CONTENT MODERATION ====================
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
        
        // Pages
        Route::prefix('pages')->name('pages.')->group(function () {
            Route::get('/', [AdminContentController::class, 'pages'])->name('index');
            Route::get('/create', [AdminContentController::class, 'createPage'])->name('create');
            Route::post('/store', [AdminContentController::class, 'storePage'])->name('store');
            Route::get('/{page}/edit', [AdminContentController::class, 'editPage'])->name('edit');
            Route::put('/{page}', [AdminContentController::class, 'updatePage'])->name('update');
            Route::post('/{page}/toggle-status', [AdminContentController::class, 'togglePageStatus'])->name('toggle-status');
            Route::delete('/{page}', [AdminContentController::class, 'deletePage'])->name('delete');
        });
        
        Route::get('/stats', [AdminContentController::class, 'getStats'])->name('stats');
    });
    
    // ==================== REPORTS & ABUSE ====================
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
    
    // ==================== SYSTEM SETTINGS ====================
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
    
    // ==================== ACTIVITY LOGS ====================
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [AdminActivityLogController::class, 'index'])->name('index');
        Route::get('/stats', [AdminActivityLogController::class, 'stats'])->name('stats');
        Route::get('/export', [AdminActivityLogController::class, 'export'])->name('export');
        Route::get('/stream', [AdminActivityLogController::class, 'stream'])->name('stream');
        Route::post('/clear', [AdminActivityLogController::class, 'clear'])->name('clear');
        Route::get('/{log}', [AdminActivityLogController::class, 'show'])->name('show');
    });
    
    // ==================== ADMIN NOTIFICATIONS ====================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::get('/recent', [AdminNotificationController::class, 'getRecent'])->name('recent');
        Route::post('/{notification}/read', [AdminNotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    });
    
    // ==================== ADMIN PROFILE ====================
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