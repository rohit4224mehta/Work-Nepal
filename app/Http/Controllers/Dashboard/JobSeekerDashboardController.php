<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class JobSeekerDashboardController extends Controller
{
    /**
     * Display the job seeker dashboard.
     */
    public function index(): View
    {
        $user = auth()->user()->loadMissing([
            'skills',
            'education',
            'experience',
            'jobPreference',
            'savedJobs'
        ]);

        // Calculate profile completion
        $completion = $this->calculateProfileCompletion($user);
        
        // Get missing profile items for suggestions (returns array)
        $missingItems = $this->getMissingProfileItems($user);
        
        // Get recommended jobs based on user profile
        $recommendedJobs = $this->getRecommendedJobs($user);
        
        // Get recent applications with status
        $recentApplications = $user->jobApplications()
            ->with(['jobPosting.company'])
            ->latest()
            ->take(5)
            ->get();
        
        // Get application statistics
        $stats = $this->getApplicationStats($user);
        
        // Suggested jobs (legacy - keeping for backward compatibility)
        $suggestedJobs = JobPosting::where('status', 'active')
            ->where('verification_status', 'verified')
            ->with('company')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.jobseeker.index', compact(
            'user',
            'completion',
            'missingItems',
            'recommendedJobs',
            'suggestedJobs',
            'recentApplications',
            'stats'
        ));
    }

    /**
     * Calculate profile completion percentage
     */
    private function calculateProfileCompletion($user): int
    {
        $weights = [
            'photo' => 10,
            'basic_info' => 10,
            'headline' => 10,
            'summary' => 10,
            'skills' => 15,
            'education' => 15,
            'experience' => 15,
            'resume' => 10,
            'preferences' => 5,
        ];

        $score = 0;

        // Photo (10%)
        if ($user->profile_photo_path) {
            $score += $weights['photo'];
        }

        // Basic Info (10%)
        $basicInfoScore = 0;
        if ($user->name) $basicInfoScore += 2;
        if ($user->email) $basicInfoScore += 2;
        if ($user->mobile) $basicInfoScore += 2;
        if ($user->gender && $user->gender !== 'prefer_not_to_say') $basicInfoScore += 2;
        if ($user->date_of_birth) $basicInfoScore += 2;
        $score += min($weights['basic_info'], $basicInfoScore);

        // Headline (10%)
        if ($user->headline) {
            $wordCount = str_word_count($user->headline);
            if ($wordCount >= 5) {
                $score += $weights['headline'];
            } elseif ($wordCount >= 3) {
                $score += ($weights['headline'] * 0.7);
            } else {
                $score += ($weights['headline'] * 0.4);
            }
        }

        // Summary (10%)
        if ($user->summary) {
            $charCount = strlen($user->summary);
            if ($charCount >= 500) {
                $score += $weights['summary'];
            } elseif ($charCount >= 200) {
                $score += ($weights['summary'] * 0.7);
            } elseif ($charCount >= 50) {
                $score += ($weights['summary'] * 0.4);
            }
        }

        // Skills (15%)
        if ($user->skills && $user->skills->count() > 0) {
            $skillCount = $user->skills->count();
            if ($skillCount >= 8) {
                $score += $weights['skills'];
            } elseif ($skillCount >= 5) {
                $score += ($weights['skills'] * 0.7);
            } elseif ($skillCount >= 3) {
                $score += ($weights['skills'] * 0.4);
            } elseif ($skillCount >= 1) {
                $score += ($weights['skills'] * 0.2);
            }
        }

        // Education (15%)
        if ($user->education && $user->education->count() > 0) {
            $educationScore = 0;
            foreach ($user->education as $edu) {
                $entryScore = 0;
                if ($edu->degree) $entryScore += 3;
                if ($edu->field_of_study) $entryScore += 3;
                if ($edu->institution) $entryScore += 3;
                if ($edu->start_date) $entryScore += 3;
                if ($edu->description) $entryScore += 3;
                $educationScore += min(15, $entryScore);
            }
            $averageScore = ($educationScore / $user->education->count());
            $score += min($weights['education'], $averageScore);
        }

        // Experience (15%)
        if ($user->experience && $user->experience->count() > 0) {
            $experienceScore = 0;
            foreach ($user->experience as $exp) {
                $entryScore = 0;
                if ($exp->position) $entryScore += 3;
                if ($exp->company_name) $entryScore += 3;
                if ($exp->start_date) $entryScore += 3;
                if ($exp->description) $entryScore += 3;
                if ($exp->end_date || $exp->is_current) $entryScore += 3;
                $experienceScore += min(15, $entryScore);
            }
            $averageScore = ($experienceScore / $user->experience->count());
            $score += min($weights['experience'], $averageScore);
        }

        // Resume (10%)
        if ($user->resume_path) {
            $score += $weights['resume'];
        }

        // Preferences (5%)
        if ($user->jobPreference) {
            $prefScore = 0;
            if ($user->jobPreference->preferred_location) $prefScore += 2;
            if ($user->jobPreference->preferred_job_type) $prefScore += 2;
            if ($user->jobPreference->expected_salary) $prefScore += 1;
            $score += min($weights['preferences'], $prefScore);
        }

        return min(100, (int) round($score));
    }

    /**
     * Get missing profile items for suggestions
     */
    private function getMissingProfileItems($user): array
    {
        $missing = [];

        if (!$user->profile_photo_path) {
            $missing[] = [
                'item' => 'profile_photo',
                'message' => 'Upload Profile Photo',
                'url' => route('profile.edit') . '#photo',
                'priority' => 'low'
            ];
        }

        if (!$user->resume_path) {
            $missing[] = [
                'item' => 'resume',
                'message' => 'Upload Resume',
                'url' => route('profile.edit') . '#resume',
                'priority' => 'high'
            ];
        }

        if (!$user->skills || $user->skills->count() < 3) {
            $remaining = 3 - ($user->skills->count() ?? 0);
            $missing[] = [
                'item' => 'skills',
                'message' => $remaining > 0 ? "Add {$remaining} more skills" : 'Add Skills',
                'url' => route('profile.edit') . '#skills',
                'priority' => 'high'
            ];
        }

        if (!$user->headline) {
            $missing[] = [
                'item' => 'headline',
                'message' => 'Add Professional Headline',
                'url' => route('profile.edit') . '#headline',
                'priority' => 'medium'
            ];
        }

        if (!$user->summary) {
            $missing[] = [
                'item' => 'summary',
                'message' => 'Add Professional Summary',
                'url' => route('profile.edit') . '#summary',
                'priority' => 'medium'
            ];
        }

        if (!$user->education || $user->education->count() == 0) {
            $missing[] = [
                'item' => 'education',
                'message' => 'Add Education',
                'url' => route('education.create'),
                'priority' => 'medium'
            ];
        }

        if (!$user->experience || $user->experience->count() == 0) {
            $missing[] = [
                'item' => 'experience',
                'message' => 'Add Work Experience',
                'url' => route('experience.create'),
                'priority' => 'medium'
            ];
        }

        if (!$user->jobPreference) {
            $missing[] = [
                'item' => 'preferences',
                'message' => 'Set Job Preferences',
                'url' => route('profile.edit') . '#preferences',
                'priority' => 'low'
            ];
        }

        return $missing;
    }

    /**
     * Get recommended jobs based on user profile
     */
    private function getRecommendedJobs($user)
    {
        $userSkills = $user->skills ? $user->skills->pluck('name')->toArray() : [];
        $preferences = $user->jobPreference;
        
        $query = JobPosting::query()
            ->where('status', 'active')
            ->where('verification_status', 'verified')
            ->whereDate('deadline', '>=', now())
            ->with('company');

        // Match by skills
        if (!empty($userSkills)) {
            $query->where(function ($q) use ($userSkills) {
                foreach ($userSkills as $skill) {
                    $q->orWhere('title', 'LIKE', "%{$skill}%")
                      ->orWhere('description', 'LIKE', "%{$skill}%");
                }
            });
        }

        // Match by location preference
        if ($preferences && $preferences->preferred_location) {
            $query->where('location', 'LIKE', '%' . $preferences->preferred_location . '%');
        }

        // Match by job type preference
        if ($preferences && $preferences->preferred_job_type) {
            $jobTypes = explode(',', $preferences->preferred_job_type);
            $query->whereIn('job_type', $jobTypes);
        }

        // Exclude jobs already applied to
        $appliedJobIds = $user->jobApplications()->pluck('job_posting_id')->toArray();
        if (!empty($appliedJobIds)) {
            $query->whereNotIn('id', $appliedJobIds);
        }

        // Calculate match score and order by relevance
        $jobs = $query->latest()->limit(6)->get();

        // Add match percentage to each job
        foreach ($jobs as $job) {
            $job->match_percentage = $this->calculateJobMatch($job, $userSkills, $preferences);
        }

        // Sort by match percentage
        return $jobs->sortByDesc('match_percentage')->values();
    }

    /**
     * Calculate job match percentage
     */
    private function calculateJobMatch($job, $userSkills, $preferences): int
    {
        $score = 0;
        $totalWeight = 0;

        // Skills match (40% weight)
        if (!empty($userSkills)) {
            $skillWeight = 40;
            $totalWeight += $skillWeight;
            
            $jobText = strtolower($job->title . ' ' . $job->description);
            $matchCount = 0;
            
            foreach ($userSkills as $skill) {
                if (strpos($jobText, strtolower($skill)) !== false) {
                    $matchCount++;
                }
            }
            
            $skillMatchPercentage = ($matchCount / count($userSkills)) * 100;
            $score += ($skillWeight * $skillMatchPercentage / 100);
        }

        // Location match (20% weight)
        if ($preferences && $preferences->preferred_location) {
            $locationWeight = 20;
            $totalWeight += $locationWeight;
            
            if (stripos($job->location, $preferences->preferred_location) !== false) {
                $score += $locationWeight;
            }
        }

        // Job type match (20% weight)
        if ($preferences && $preferences->preferred_job_type) {
            $jobTypeWeight = 20;
            $totalWeight += $jobTypeWeight;
            
            $preferredTypes = explode(',', $preferences->preferred_job_type);
            if (in_array($job->job_type, $preferredTypes)) {
                $score += $jobTypeWeight;
            }
        }

        // Recent job bonus (10% weight)
        $recentWeight = 10;
        $totalWeight += $recentWeight;
        
        if ($job->created_at->diffInDays(now()) <= 7) {
            $score += $recentWeight;
        }

        // Calculate final percentage
        return $totalWeight > 0 ? (int) round(($score / $totalWeight) * 100) : 0;
    }

    /**
     * Get application statistics
     */
    private function getApplicationStats($user): array
    {
        $applications = $user->jobApplications();

        return [
            'total_applications' => $applications->count(),
            'pending' => (clone $applications)->whereIn('status', ['applied', 'viewed'])->count(),
            'shortlisted' => (clone $applications)->where('status', 'shortlisted')->count(),
            'rejected' => (clone $applications)->where('status', 'rejected')->count(),
            'interviews' => (clone $applications)->where('status', 'interview')->count(),
            'hired' => (clone $applications)->where('status', 'hired')->count(),
            'saved_jobs' => $user->savedJobs()->count(),
            'profile_views' => $user->profile_views ?? 0,
        ];
    }

    /**
     * Save a job (AJAX endpoint)
     */
    public function saveJob(Request $request, $jobId)
    {
        try {
            $user = auth()->user();
            
            // Check if already saved
            if ($user->savedJobs()->where('job_posting_id', $jobId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Job already saved'
                ]);
            }

            // Save the job
            $user->savedJobs()->attach($jobId);

            return response()->json([
                'success' => true,
                'message' => 'Job saved successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save job'
            ], 500);
        }
    }

    /**
     * Unsave a job (AJAX endpoint)
     */
    public function unsaveJob(Request $request, $jobId)
    {
        try {
            $user = auth()->user();
            $user->savedJobs()->detach($jobId);

            return response()->json([
                'success' => true,
                'message' => 'Job removed from saved'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove job'
            ], 500);
        }
    }
}