<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Education;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        // Load related data safely
        $user->loadMissing([
            'education',
            'experience',
            // 'skills', // if you implement many-to-many later
        ]);

        return view('profile.show', compact('user', 'isOwnProfile'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit(): View
{
    $user = auth()->user();

    // Convert skills collection to comma-separated string for the hidden input
    $skillsString = $user->skills?->pluck('name')->implode(', ') ?? '';

    return view('profile.edit', compact('user', 'skillsString'));
}

    /**
     * Update basic profile information (name, headline, summary, photo, resume, etc.)
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'gender'        => ['nullable', 'in:male,female,other,prefer_not_to_say'],
            'date_of_birth' => ['nullable', 'date', 'before:-18 years'],
            'mobile'        => ['nullable', 'string', 'regex:/^[0-9]{10}$/', Rule::unique('users')->ignore($user->id)],
            'headline'      => ['nullable', 'string', 'max:150'],
            'summary'       => ['nullable', 'string', 'max:2000'],
            'photo'         => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'resume'        => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'skills'        => ['nullable', 'array'], // comma-separated string or array
            'preferences'   => ['nullable', 'array'], // job preferences
        ]);

        // Update basic fields
        $user->update($request->only([
            'name', 'gender', 'date_of_birth', 'mobile', 'headline', 'summary'
        ]));

        // Skills (store as JSON or comma-separated)
        if ($request->filled('skills')) {
            $skills = is_array($request->skills) ? $request->skills : explode(',', $request->skills);
            $user->update(['skills' => json_encode(array_filter(array_map('trim', $skills)))]);
        }

        // Job Preferences (store as JSON)
        if ($request->filled('preferences')) {
            $user->update(['preferences' => json_encode($request->preferences)]);
        }

        // Profile photo
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
            ->with('status', 'Profile updated successfully!');
    }

    // ────────────────────────────────────────────────
    // Photo Management
    // ────────────────────────────────────────────────

    public function photo(): View
    {
        return view('profile.photo');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $path = $request->file('photo')->store('profile-photos', 'public');
        $user->update(['profile_photo_path' => $path]);

        return redirect()->route('profile.show', $user)
            ->with('status', 'Profile photo updated successfully!');
    }

    public function removePhoto()
    {
        $user = auth()->user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->update(['profile_photo_path' => null]);
        }

        return redirect()->route('profile.show', $user)
            ->with('status', 'Profile photo removed successfully.');
    }

    // ────────────────────────────────────────────────
    // Password Management
    // ────────────────────────────────────────────────

    public function password(): View
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ]);

        auth()->user()->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('profile.show', auth()->user())
            ->with('status', 'Password updated successfully!');
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

        auth()->user()->education()->create($validated);

        return redirect()->route('profile.edit')
            ->with('status', 'Education added successfully!');
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

        $education->update($validated);

        return redirect()->route('profile.edit')
            ->with('status', 'Education updated successfully!');
    }

    public function destroyEducation(Education $education)
    {
        $this->authorize('delete', $education);

        $education->delete();

        return redirect()->route('profile.edit')
            ->with('status', 'Education removed successfully!');
    }

    // ────────────────────────────────────────────────
    // Experience CRUD (similar to Education)
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
            'description'    => 'nullable|string|max:2000',
        ]);

        auth()->user()->experience()->create($validated);

        return redirect()->route('profile.edit')
            ->with('status', 'Experience added successfully!');
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
            'description'    => 'nullable|string|max:2000',
        ]);

        $experience->update($validated);

        return redirect()->route('profile.edit')
            ->with('status', 'Experience updated successfully!');
    }

    public function destroyExperience(Experience $experience)
    {
        $this->authorize('delete', $experience);

        $experience->delete();

        return redirect()->route('profile.edit')
            ->with('status', 'Experience removed successfully!');
    }

    // ────────────────────────────────────────────────
    // Delete Profile (Dangerous – use with confirmation page)
    // ────────────────────────────────────────────────

    public function destroy()
    {
        $user = auth()->user();
        $user->delete();

        return redirect('/')->with('status', 'Your profile has been deleted.');
    }
}