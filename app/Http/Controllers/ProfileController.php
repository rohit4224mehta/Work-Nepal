<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Education;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;

class ProfileController extends Controller
{
    /**
     * Display the user's profile (public view).
     */
    public function show(User $user): View
    {
        $isOwnProfile = auth()->check() && auth()->id() === $user->id;

        // Load relations safely with ordering
        $user->loadMissing([
            'education' => fn($q) => $q->orderBy('start_date', 'desc'),
            'experience' => fn($q) => $q->orderBy('start_date', 'desc'),
        ]);

        return view('profile.show', compact('user', 'isOwnProfile'));
    }

    /**
     * Show the profile edit form.
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
            'skills'
        ]);

        // Calculate dynamic profile completion
        $completion = $this->calculateProfileCompletion($user);

        // Get skills as comma-separated string for the form
        $skillsString = $user->skills->pluck('name')->implode(', ');

        // Parse preferences
        $preferences = $user->preferences ? json_decode($user->preferences, true) : [];

        return view('profile.edit', compact('user', 'skillsString', 'preferences', 'completion'));
    }

    /**
     * Calculate profile completion percentage dynamically
     */
    private function calculateProfileCompletion($user): int
    {
        $score = 0;
        $weights = [
            'name' => 5,
            'profile_photo' => 10,
            'mobile' => 8,
            'email_verified' => 7,
            'date_of_birth' => 5,
            'gender' => 5,
            'headline' => 15,
            'summary' => 15,
            'education' => 15,
            'experience' => 15,
            'skills' => 10,
            'preferences' => 5,
        ];

        // Basic Information (35 points total)
        if ($user->name) $score += $weights['name'];
        
        if ($user->profile_photo_path) {
            $score += $weights['profile_photo'];
        } else {
            // Check if using default avatar
            $defaultAvatars = ['default-avatar.png', 'default-avatar.jpg', 'default-profile.png'];
            $isDefault = false;
            
            if ($user->profile_photo_url) {
                foreach ($defaultAvatars as $default) {
                    if (str_contains($user->profile_photo_url, $default)) {
                        $isDefault = true;
                        break;
                    }
                }
            }
            
            if (!$isDefault && $user->profile_photo_url) {
                $score += $weights['profile_photo'];
            }
        }
        
        if ($user->mobile) $score += $weights['mobile'];
        if ($user->email_verified_at) $score += $weights['email_verified'];
        if ($user->date_of_birth) $score += $weights['date_of_birth'];
        
        if ($user->gender && $user->gender !== 'prefer_not_to_say') {
            $score += $weights['gender'];
        }

        // Professional Information (30 points total)
        if ($user->headline) {
            // Bonus for more detailed headline (word count)
            $wordCount = str_word_count($user->headline);
            if ($wordCount >= 5) {
                $score += $weights['headline']; // Full points for detailed headline
            } elseif ($wordCount >= 3) {
                $score += ($weights['headline'] * 0.7); // 70% for decent headline
            } else {
                $score += ($weights['headline'] * 0.4); // 40% for basic headline
            }
        }

        if ($user->summary) {
            // Bonus for longer summaries
            $charCount = strlen($user->summary);
            if ($charCount >= 500) {
                $score += $weights['summary']; // Full points for detailed summary
            } elseif ($charCount >= 200) {
                $score += ($weights['summary'] * 0.7); // 70% for medium summary
            } elseif ($charCount >= 50) {
                $score += ($weights['summary'] * 0.4); // 40% for short summary
            }
        }

        // Education (15 points total)
        if ($user->education && $user->education->count() > 0) {
            $educationScore = 0;
            foreach ($user->education as $edu) {
                // Check completeness of each education entry
                $entryScore = 0;
                if ($edu->degree) $entryScore += 2;
                if ($edu->field_of_study) $entryScore += 2;
                if ($edu->institution) $entryScore += 2;
                if ($edu->start_date) $entryScore += 2;
                if ($edu->description && strlen($edu->description) > 50) $entryScore += 2;
                
                $educationScore += min(10, $entryScore); // Max 10 per entry
            }
            
            // Average of all entries, max 15 points
            $averageScore = ($educationScore / $user->education->count()) * 1.5;
            $score += min($weights['education'], $averageScore);
        }

        // Experience (15 points total)
        if ($user->experience && $user->experience->count() > 0) {
            $experienceScore = 0;
            foreach ($user->experience as $exp) {
                // Check completeness of each experience entry
                $entryScore = 0;
                if ($exp->position) $entryScore += 2;
                if ($exp->company_name) $entryScore += 2;
                if ($exp->start_date) $entryScore += 2;
                if ($exp->description && strlen($exp->description) > 50) $entryScore += 2;
                if ($exp->end_date || $exp->is_current) $entryScore += 2;
                
                $experienceScore += min(10, $entryScore); // Max 10 per entry
            }
            
            // Average of all entries, max 15 points
            $averageScore = ($experienceScore / $user->experience->count()) * 1.5;
            $score += min($weights['experience'], $averageScore);
        }

        // Skills (10 points total)
        if ($user->skills && $user->skills->count() > 0) {
            $skillCount = $user->skills->count();
            if ($skillCount >= 8) {
                $score += $weights['skills']; // Full points for 8+ skills
            } elseif ($skillCount >= 5) {
                $score += ($weights['skills'] * 0.7); // 70% for 5-7 skills
            } elseif ($skillCount >= 3) {
                $score += ($weights['skills'] * 0.4); // 40% for 3-4 skills
            } elseif ($skillCount >= 1) {
                $score += ($weights['skills'] * 0.2); // 20% for 1-2 skills
            }
        }

        // Job Preferences (5 points total)
        if ($user->preferences) {
            $preferences = is_string($user->preferences) ? json_decode($user->preferences, true) : $user->preferences;
            
            if (is_array($preferences)) {
                $prefScore = 0;
                if (!empty($preferences['preferred_locations'])) $prefScore += 2;
                if (!empty($preferences['job_types']) && count($preferences['job_types']) > 0) $prefScore += 1.5;
                if (!empty($preferences['expected_salary'])) $prefScore += 1.5;
                
                $score += min($weights['preferences'], $prefScore);
            }
        }

        // Cap at 100 and round
        return min(100, (int) round($score));
    }

    /**
     * Get detailed completion breakdown for API/JS usage
     */
    public function getCompletionBreakdown(): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user()->loadMissing(['education', 'experience', 'skills']);
        
        $breakdown = [
            'name' => ['completed' => !empty($user->name), 'weight' => 5],
            'profile_photo' => ['completed' => !empty($user->profile_photo_path), 'weight' => 10],
            'mobile' => ['completed' => !empty($user->mobile), 'weight' => 8],
            'email_verified' => ['completed' => !empty($user->email_verified_at), 'weight' => 7],
            'date_of_birth' => ['completed' => !empty($user->date_of_birth), 'weight' => 5],
            'gender' => ['completed' => ($user->gender && $user->gender !== 'prefer_not_to_say'), 'weight' => 5],
            'headline' => ['completed' => !empty($user->headline), 'weight' => 15],
            'summary' => ['completed' => !empty($user->summary), 'weight' => 15],
            'education' => ['completed' => ($user->education && $user->education->count() > 0), 'weight' => 15],
            'experience' => ['completed' => ($user->experience && $user->experience->count() > 0), 'weight' => 15],
            'skills' => ['completed' => ($user->skills && $user->skills->count() > 0), 'weight' => 10],
            'preferences' => ['completed' => !empty($user->preferences), 'weight' => 5],
        ];

        $total = $this->calculateProfileCompletion($user);

        return response()->json([
            'total' => $total,
            'breakdown' => $breakdown,
            'message' => $this->getCompletionMessage($total)
        ]);
    }

    /**
     * Get appropriate message based on completion percentage
     */
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
     * Get skills as comma-separated string
     */
    private function getSkillsString(User $user): string
    {
        if (!$user->skills) return '';

        if (is_string($user->skills)) {
            $decoded = json_decode($user->skills, true);
            return is_array($decoded) ? implode(', ', $decoded) : '';
        }

        if (is_array($user->skills)) {
            return implode(', ', $user->skills);
        }

        return '';
    }

    /**
     * Update user's basic profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'gender'        => ['nullable', 'in:male,female,other,prefer_not_to_say'],
            'date_of_birth' => ['nullable', 'date', 'before:-18 years'],
            'mobile'        => ['nullable', 'regex:/^[0-9]{10}$/', Rule::unique('users')->ignore($user->id)],
            'headline'      => ['nullable', 'string', 'max:150'],
            'summary'       => ['nullable', 'string', 'max:5000'],
            'photo'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'resume'        => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'skills'        => ['nullable', 'string'],
        ]);

        // Update core fields
        $user->update($request->only([
            'name', 'gender', 'date_of_birth', 'mobile', 'headline', 'summary'
        ]));

        // Skills - store as JSON array
        if ($request->filled('skills')) {
            $skills = array_filter(array_map('trim', explode(',', $request->skills)));
            $user->update(['skills' => json_encode($skills)]);
        }

        // Photo
        if ($request->hasFile('photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->update(['profile_photo_path' => $path]);
        }

        // Resume
        if ($request->hasFile('resume')) {
            if ($user->resume_path) {
                Storage::disk('public')->delete($user->resume_path);
            }
            $path = $request->file('resume')->store('resumes', 'public');
            $user->update(['resume_path' => $path]);
        }

        return redirect()->route('profile.show', $user)
            ->with('success', 'Profile updated successfully!');
    }

    // ────────────────────────────────────────────────
    // Photo Management (separate routes if needed)
    // ────────────────────────────────────────────────

    public function photo(): View
    {
        return view('profile.photo');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $path = $request->file('photo')->store('profile-photos', 'public');
        $user->update(['profile_photo_path' => $path]);

        return redirect()->route('profile.show', $user)
            ->with('success', 'Profile photo updated!');
    }

    public function removePhoto()
    {
        $user = auth()->user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->update(['profile_photo_path' => null]);
        }

        return redirect()->route('profile.show', $user)
            ->with('success', 'Profile photo removed.');
    }

    // ────────────────────────────────────────────────
    // Password Change
    // ────────────────────────────────────────────────

    public function password(): View
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth()->user()->password)) {
                    $fail('Current password is incorrect.');
                }
            }],
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show', auth()->user())
            ->with('success', 'Password changed successfully!');
    }

    // ────────────────────────────────────────────────
    // Education CRUD
    // ────────────────────────────────────────────────

    public function storeEducation(Request $request)
    {
        $validated = $request->validate([
            'degree'         => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'institution'    => 'required|string|max:255',
            'location'       => 'nullable|string|max:255',
            'start_date'     => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'is_current'     => 'boolean',
            'description'    => 'nullable|string|max:2000',
        ]);

        if ($request->boolean('is_current')) {
            $validated['end_date'] = null;
        }

        auth()->user()->education()->create($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Education added successfully!');
    }

    public function updateEducation(Request $request, Education $education)
    {
        $this->authorize('update', $education);

        $validated = $request->validate([
            'degree'         => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'institution'    => 'required|string|max:255',
            'location'       => 'nullable|string|max:255',
            'start_date'     => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'is_current'     => 'boolean',
            'description'    => 'nullable|string|max:2000',
        ]);

        if ($request->boolean('is_current')) {
            $validated['end_date'] = null;
        }

        $education->update($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Education updated!');
    }

    public function destroyEducation(Education $education)
    {
        $this->authorize('delete', $education);

        $education->delete();

        return redirect()->route('profile.edit')
            ->with('success', 'Education removed.');
    }

    // ────────────────────────────────────────────────
    // Experience CRUD (mirror of Education)
    // ────────────────────────────────────────────────

    public function storeExperience(Request $request)
    {
        $validated = $request->validate([
            'position'       => 'required|string|max:255',
            'company'        => 'required|string|max:255',
            'location'       => 'nullable|string|max:255',
            'start_date'     => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'is_current'     => 'boolean',
            'description'    => 'nullable|string|max:5000',
        ]);

        if ($request->boolean('is_current')) {
            $validated['end_date'] = null;
        }

        auth()->user()->experience()->create($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Experience added!');
    }

    public function updateExperience(Request $request, Experience $experience)
    {
        $this->authorize('update', $experience);

        $validated = $request->validate([
            'position'       => 'required|string|max:255',
            'company'        => 'required|string|max:255',
            'location'       => 'nullable|string|max:255',
            'start_date'     => 'required|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'is_current'     => 'boolean',
            'description'    => 'nullable|string|max:5000',
        ]);

        if ($request->boolean('is_current')) {
            $validated['end_date'] = null;
        }

        $experience->update($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Experience updated!');
    }

    public function destroyExperience(Experience $experience)
    {
        $this->authorize('delete', $experience);

        $experience->delete();

        return redirect()->route('profile.edit')
            ->with('success', 'Experience removed.');
    }

    // ────────────────────────────────────────────────
    // Delete Account (with confirmation)
    // ────────────────────────────────────────────────

    public function confirmDelete(): View
    {
        return view('profile.delete');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth()->user()->password)) {
                    $fail('The password is incorrect.');
                }
            }],
        ]);

        $user = auth()->user();

        // Cleanup files
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
        if ($user->resume_path) {
            Storage::disk('public')->delete($user->resume_path);
        }

        auth()->logout();
        $user->delete();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}