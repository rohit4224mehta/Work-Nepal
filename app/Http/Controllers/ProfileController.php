<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\JobPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the user's profile.
     */
    public function edit(): View
    {
        $user = auth()->user()->loadMissing([
            'education' => function ($query) {
                $query->orderBy('start_date', 'desc');
            },
            'experience' => function ($query) {
                $query->orderBy('start_date', 'desc');
            },
            'skills',
            'jobPreference'
        ]);

        // Calculate dynamic profile completion
        $completion = $this->calculateProfileCompletion($user);

        // Get skills as array for the form
        $userSkills = $user->skills->pluck('name')->toArray();

        // Get job preferences from job_preferences table
        $preferences = [
            'preferred_locations' => $user->jobPreference->preferred_location ?? '',
            'job_types' => $user->jobPreference ? 
                          ($user->jobPreference->preferred_job_type ? 
                           explode(',', $user->jobPreference->preferred_job_type) : []) : [],
            'expected_salary' => $user->jobPreference->expected_salary ?? '',
            'fresher' => $user->jobPreference->fresher ?? false,
        ];

        // Job types options
        $jobTypes = [
            'full-time' => 'Full Time',
            'part-time' => 'Part Time',
            'contract' => 'Contract',
            'internship' => 'Internship',
            'remote' => 'Remote',
            'freelance' => 'Freelance',
        ];

        return view('profile.edit', compact(
            'user', 
            'completion', 
            'userSkills', 
            'preferences',
            'jobTypes'
        ));
    }

    /**
     * Calculate profile completion percentage dynamically
     */
    private function calculateProfileCompletion($user): int
    {
        $score = 0;
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

        // Photo (10%)
        if ($user->profile_photo_path) {
            $score += $weights['photo'];
        }

        // Basic Info (10%) - name, email, mobile, gender, dob
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
     * Update the user's basic profile information.
     */
    public function update(Request $request)
    {
        try {
            $user = auth()->user();

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'gender' => ['nullable', 'in:male,female,other,prefer_not_to_say'],
                'date_of_birth' => ['nullable', 'date', 'before:today'],
                'mobile' => ['nullable', 'string', 'regex:/^[0-9]{10}$/', Rule::unique('users')->ignore($user->id)],
            ]);

            $user->update($validated);

            return redirect()->route('profile.edit')->with('success', 'Basic information updated successfully!');
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            return redirect()->route('profile.edit')->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * Update profile photo
     */
    public function updatePhoto(Request $request)
    {
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $user = auth()->user();

            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->update(['profile_photo_path' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Profile photo updated successfully!',
                'photo_url' => $user->profile_photo_url
            ]);
        } catch (\Exception $e) {
            Log::error('Photo upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload photo. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove profile photo
     */
    public function removePhoto()
    {
        try {
            $user = auth()->user();

            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
                $user->update(['profile_photo_path' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile photo removed successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Photo removal error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove photo. Please try again.'
            ], 500);
        }
    }

    /**
     * Update professional headline
     */
    public function updateHeadline(Request $request)
    {
        try {
            $request->validate([
                'headline' => 'required|string|max:255',
            ]);

            auth()->user()->update(['headline' => $request->headline]);

            return response()->json([
                'success' => true,
                'message' => 'Headline updated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Headline update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update headline. Please try again.'
            ], 500);
        }
    }

    /**
     * Update professional summary
     */
    public function updateSummary(Request $request)
    {
        try {
            $request->validate([
                'summary' => 'required|string|max:5000',
            ]);

            auth()->user()->update(['summary' => $request->summary]);

            return response()->json([
                'success' => true,
                'message' => 'Summary updated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Summary update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update summary. Please try again.'
            ], 500);
        }
    }

    /**
     * Update user skills
     */
    public function updateSkills(Request $request)
    {
        try {
            $request->validate([
                'skills' => 'nullable|array',
                'skills.*' => 'string|max:50',
            ]);

            $user = auth()->user();
            
            // Clear existing skills
            $user->skills()->detach();
            
            if ($request->has('skills') && !empty($request->skills)) {
                foreach ($request->skills as $skillName) {
                    $skillName = trim($skillName);
                    if (!empty($skillName)) {
                        // Create slug from skill name
                        $slug = \Illuminate\Support\Str::slug($skillName);
                        
                        // Find or create skill
                        $skill = Skill::firstOrCreate(
                            ['name' => $skillName],
                            ['slug' => $slug]
                        );
                        
                        $user->skills()->attach($skill->id);
                    }
                }
            }

            // Reload skills
            $user->load('skills');

            return response()->json([
                'success' => true,
                'message' => 'Skills updated successfully!',
                'skills' => $user->skills->pluck('name')
            ]);
        } catch (\Exception $e) {
            Log::error('Skills update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update skills. Please try again.'
            ], 500);
        }
    }

    /**
     * Upload resume
     */
    public function uploadResume(Request $request)
    {
        try {
            $request->validate([
                'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
            ]);

            $user = auth()->user();

            if ($user->resume_path) {
                Storage::disk('public')->delete($user->resume_path);
            }

            $path = $request->file('resume')->store('resumes', 'public');
            $user->update(['resume_path' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Resume uploaded successfully!',
                'file_name' => $request->file('resume')->getClientOriginalName(),
                'file_size' => $request->file('resume')->getSize(),
            ]);
        } catch (\Exception $e) {
            Log::error('Resume upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload resume. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete resume
     */
    public function deleteResume()
    {
        try {
            $user = auth()->user();

            if ($user->resume_path) {
                Storage::disk('public')->delete($user->resume_path);
                $user->update(['resume_path' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Resume deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Resume deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete resume. Please try again.'
            ], 500);
        }
    }

    /**
     * Update job preferences
     */
    public function updatePreferences(Request $request)
    {
        try {
            $request->validate([
                'preferred_locations' => 'nullable|string|max:255',
                'job_types' => 'nullable|array',
                'job_types.*' => 'string|in:full-time,part-time,contract,internship,remote,freelance',
                'expected_salary' => 'nullable|string|max:50',
                'fresher' => 'nullable|boolean',
            ]);

            $user = auth()->user();
            
            // Convert job types array to comma-separated string
            $jobTypesString = $request->has('job_types') && !empty($request->job_types) ? 
                              implode(',', $request->job_types) : null;
            
            // Update or create job preference
            $preference = JobPreference::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'preferred_location' => $request->preferred_locations,
                    'preferred_job_type' => $jobTypesString,
                    'expected_salary' => $request->expected_salary,
                    'fresher' => $request->boolean('fresher'),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Job preferences updated successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Preferences update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update preferences. Please try again.'
            ], 500);
        }
    }

    // ────────────────────────────────────────────────
    // Education CRUD
    // ────────────────────────────────────────────────

    public function storeEducation(Request $request)
    {
        try {
            $validated = $request->validate([
                'degree' => 'required|string|max:255',
                'field_of_study' => 'required|string|max:255',
                'institution' => 'required|string|max:255',
                'location' => 'nullable|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'is_current' => 'sometimes|boolean',
                'description' => 'nullable|string|max:2000',
            ]);

            if ($request->boolean('is_current')) {
                $validated['end_date'] = null;
            }

            $validated['user_id'] = auth()->id();
            
            $education = Education::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Education added successfully!',
                'education' => $education
            ]);
        } catch (\Exception $e) {
            Log::error('Store education error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add education. Please try again.'
            ], 500);
        }
    }

    public function updateEducation(Request $request, $id)
    {
        try {
            $education = Education::findOrFail($id);
            
            // Check authorization
            if ($education->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            $validated = $request->validate([
                'degree' => 'required|string|max:255',
                'field_of_study' => 'required|string|max:255',
                'institution' => 'required|string|max:255',
                'location' => 'nullable|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'is_current' => 'sometimes|boolean',
                'description' => 'nullable|string|max:2000',
            ]);

            if ($request->boolean('is_current')) {
                $validated['end_date'] = null;
            }

            $education->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Education updated successfully!',
                'education' => $education
            ]);
        } catch (\Exception $e) {
            Log::error('Update education error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update education. Please try again.'
            ], 500);
        }
    }

    public function destroyEducation($id)
    {
        try {
            $education = Education::findOrFail($id);
            
            // Check authorization
            if ($education->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }
            
            $education->delete();

            return response()->json([
                'success' => true,
                'message' => 'Education removed successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete education error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete education. Please try again.'
            ], 500);
        }
    }

    // ────────────────────────────────────────────────
    // Experience CRUD
    // ────────────────────────────────────────────────

    public function storeExperience(Request $request)
    {
        try {
            $validated = $request->validate([
                'position' => 'required|string|max:255',
                'company_name' => 'required|string|max:255',
                'location' => 'nullable|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'is_current' => 'sometimes|boolean',
                'description' => 'nullable|string|max:5000',
            ]);

            if ($request->boolean('is_current')) {
                $validated['end_date'] = null;
            }

            $validated['user_id'] = auth()->id();
            
            $experience = Experience::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Experience added successfully!',
                'experience' => $experience
            ]);
        } catch (\Exception $e) {
            Log::error('Store experience error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add experience. Please try again.'
            ], 500);
        }
    }

    public function updateExperience(Request $request, $id)
    {
        try {
            $experience = Experience::findOrFail($id);
            
            // Check authorization
            if ($experience->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }

            $validated = $request->validate([
                'position' => 'required|string|max:255',
                'company_name' => 'required|string|max:255',
                'location' => 'nullable|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'is_current' => 'sometimes|boolean',
                'description' => 'nullable|string|max:5000',
            ]);

            if ($request->boolean('is_current')) {
                $validated['end_date'] = null;
            }

            $experience->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Experience updated successfully!',
                'experience' => $experience
            ]);
        } catch (\Exception $e) {
            Log::error('Update experience error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update experience. Please try again.'
            ], 500);
        }
    }

    public function destroyExperience($id)
    {
        try {
            $experience = Experience::findOrFail($id);
            
            // Check authorization
            if ($experience->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }
            
            $experience->delete();

            return response()->json([
                'success' => true,
                'message' => 'Experience removed successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete experience error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete experience. Please try again.'
            ], 500);
        }
    }

    /**
     * Get profile completion data for AJAX
     */
    public function getCompletionData()
    {
        try {
            $user = auth()->user()->loadMissing(['education', 'experience', 'skills', 'jobPreference']);
            $completion = $this->calculateProfileCompletion($user);

            return response()->json([
                'success' => true,
                'completion' => $completion,
                'message' => $this->getCompletionMessage($completion)
            ]);
        } catch (\Exception $e) {
            Log::error('Completion data error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get completion data.'
            ], 500);
        }
    }

    private function getCompletionMessage(int $completion): string
    {
        if ($completion >= 90) {
            return 'Excellent! Your profile is complete and ready to impress recruiters!';
        } elseif ($completion >= 75) {
            return 'Great progress! Just a few more details to make your profile stand out.';
        } elseif ($completion >= 50) {
            return 'Good start! Adding more details will increase your visibility to employers.';
        } elseif ($completion >= 25) {
            return 'You\'re on your way! Complete your profile to get better job matches.';
        } else {
            return 'Add skills, experience & preferences to reach 80%+ completion.';
        }
    }

    /**
     * Get single education record
     */
    public function getEducation($id)
    {
        try {
            $education = Education::findOrFail($id);
            
            if ($education->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'education' => $education
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Education record not found.'
            ], 404);
        }
    }

    /**
     * Get single experience record
     */
    public function getExperience($id)
    {
        try {
            $experience = Experience::findOrFail($id);
            
            if ($experience->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'experience' => $experience
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Experience record not found.'
            ], 404);
        }
    }
}