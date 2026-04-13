<?php
// app/Http/Controllers/JobAlertController.php

namespace App\Http\Controllers;

use App\Models\JobAlert;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobAlertController extends Controller
{
    /**
     * Display user's job alerts
     */
    public function index(): View
    {
        $alerts = auth()->user()->jobAlerts()->orderBy('created_at', 'desc')->get();
        
        return view('notifications.job-alerts', compact('alerts'));
    }
    
    /**
     * Store a new job alert
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'keywords' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'job_type' => 'nullable|string|in:full-time,part-time,contract,internship,remote',
            'category' => 'nullable|string|max:100',
            'experience_level' => 'nullable|string|in:entry,mid,senior,lead,executive',
            'salary_min' => 'nullable|integer|min:0',
            'salary_max' => 'nullable|integer|min:0',
            'frequency' => 'required|in:daily,weekly,instant',
        ]);
        
        $validated['user_id'] = auth()->id();
        $validated['is_active'] = true;
        
        $alert = JobAlert::create($validated);
        
        // Send immediate test notification if instant
        if ($alert->frequency === 'instant') {
            $this->sendInstantAlert($alert);
        }
        
        return redirect()->route('job-alerts.index')
            ->with('success', 'Job alert created successfully');
    }
    
    /**
     * Update a job alert
     */
    public function update(Request $request, JobAlert $alert)
    {
        if ($alert->user_id !== auth()->id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'keywords' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'job_type' => 'nullable|string|in:full-time,part-time,contract,internship,remote',
            'category' => 'nullable|string|max:100',
            'experience_level' => 'nullable|string|in:entry,mid,senior,lead,executive',
            'salary_min' => 'nullable|integer|min:0',
            'salary_max' => 'nullable|integer|min:0',
            'frequency' => 'required|in:daily,weekly,instant',
        ]);
        
        $alert->update($validated);
        
        return redirect()->route('job-alerts.index')
            ->with('success', 'Job alert updated successfully');
    }
    
    /**
     * Delete a job alert
     */
    public function destroy(JobAlert $alert)
    {
        if ($alert->user_id !== auth()->id()) {
            abort(403);
        }
        
        $alert->delete();
        
        return redirect()->route('job-alerts.index')
            ->with('success', 'Job alert deleted successfully');
    }
    
    /**
     * Toggle job alert status
     */
    public function toggle(JobAlert $alert)
    {
        if ($alert->user_id !== auth()->id()) {
            abort(403);
        }
        
        $alert->toggleStatus();
        
        $status = $alert->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('job-alerts.index')
            ->with('success', "Job alert {$status} successfully");
    }
    
    /**
     * Send instant alert for new job
     */
    protected function sendInstantAlert(JobAlert $alert)
    {
        $jobs = $this->findMatchingJobs($alert);
        
        if ($jobs->isNotEmpty()) {
            NotificationService::jobAlert($alert->user_id, $jobs, $alert->name);
            $alert->update(['last_sent_at' => now()]);
        }
    }
    
    /**
     * Find jobs matching the alert criteria
     */
    protected function findMatchingJobs(JobAlert $alert)
    {
        $query = \App\Models\JobPosting::where('status', 'active')
            ->where('verification_status', 'verified')
            ->where(function($q) {
                $q->whereNull('deadline')
                  ->orWhere('deadline', '>=', now()->format('Y-m-d'));
            });
        
        if ($alert->keywords) {
            $keywords = explode(',', $alert->keywords);
            $query->where(function($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('title', 'LIKE', "%{$keyword}%")
                      ->orWhere('description', 'LIKE', "%{$keyword}%");
                }
            });
        }
        
        if ($alert->location) {
            $query->where('location', 'LIKE', "%{$alert->location}%");
        }
        
        if ($alert->job_type) {
            $query->where('job_type', $alert->job_type);
        }
        
        if ($alert->category) {
            $query->where('category', $alert->category);
        }
        
        if ($alert->experience_level) {
            $query->where('experience_level', $alert->experience_level);
        }
        
        // Only get jobs posted after last alert sent
        if ($alert->last_sent_at) {
            $query->where('created_at', '>', $alert->last_sent_at);
        } else {
            $query->where('created_at', '>', now()->subDays(7));
        }
        
        return $query->limit(10)->get();
    }
}