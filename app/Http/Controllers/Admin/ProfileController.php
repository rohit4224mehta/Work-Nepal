<?php
// app/Http/Controllers/Admin/ProfileController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends AdminController
{
    /**
     * Display the admin profile.
     */
    public function show(): View
    {
        $admin = auth()->user();
        
        // Get recent activity
        $recentActivities = \App\Models\ActivityLog::where('admin_id', $admin->id)
            ->with(['user'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.profile.show', compact('admin', 'recentActivities'));
    }

    /**
     * Show the form for editing the admin profile.
     */
    public function edit(): View
    {
        $admin = auth()->user();
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Update the admin profile.
     */
    public function update(Request $request): RedirectResponse
    {
        $admin = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($admin->id)],
            'mobile' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other,prefer_not_to_say'],
        ]);

        $admin->update($validated);

        // Handle photo upload if present
        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Delete old photo
            if ($admin->profile_photo_path) {
                Storage::disk('public')->delete($admin->profile_photo_path);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $admin->update(['profile_photo_path' => $path]);
        }

        // Log the action
        $this->logAdminAction(
            'profile_updated',
            "Updated own profile"
        );

        return redirect()->route('admin.profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the form for changing password.
     */
    public function password(): View
    {
        return view('admin.profile.password');
    }

    /**
     * Update the admin password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth()->user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        // Log the action
        $this->logAdminAction(
            'password_changed',
            "Changed own password"
        );

        return redirect()->route('admin.profile.show')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Remove profile photo.
     */
    public function removePhoto(): RedirectResponse
    {
        $admin = auth()->user();

        if ($admin->profile_photo_path) {
            Storage::disk('public')->delete($admin->profile_photo_path);
            $admin->update(['profile_photo_path' => null]);
        }

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profile photo removed successfully.');
    }

    /**
     * Get activity log for AJAX.
     */
    public function activityLog(): \Illuminate\Http\JsonResponse
    {
        $activities = \App\Models\ActivityLog::where('admin_id', auth()->id())
            ->with(['user'])
            ->latest()
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'activities' => $activities
        ]);
    }
}