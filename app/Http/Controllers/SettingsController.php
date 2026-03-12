<?php
// app/Http/Controllers/SettingsController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Show the settings page.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        return view('settings.index', compact('user'));
    }

    /**
     * Update profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'mobile' => ['nullable', 'string', 'max:20'],
            'language' => ['nullable', 'string', 'in:en,ne'],
            'timezone' => ['nullable', 'string'],
        ]);

        $user->update($validated);

        return redirect()->route('settings.index')->with('success', 'Profile settings updated successfully!');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth()->user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('settings.index')->with('success', 'Password updated successfully!');
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        $user = auth()->user();
        
        $preferences = [
            'email_job_alerts' => $request->boolean('email_job_alerts'),
            'email_application_updates' => $request->boolean('email_application_updates'),
            'email_messages' => $request->boolean('email_messages'),
            'email_newsletter' => $request->boolean('email_newsletter'),
            'push_job_alerts' => $request->boolean('push_job_alerts'),
            'push_application_updates' => $request->boolean('push_application_updates'),
            'push_messages' => $request->boolean('push_messages'),
        ];

        // Store preferences in user meta or a separate settings table
        // For now, we'll store in session for demo
        session(['notification_preferences' => $preferences]);

        return redirect()->route('settings.index')->with('success', 'Notification preferences updated!');
    }

    /**
     * Update privacy settings.
     */
    public function updatePrivacy(Request $request)
    {
        $settings = [
            'profile_visibility' => $request->profile_visibility,
            'show_email' => $request->boolean('show_email'),
            'show_phone' => $request->boolean('show_phone'),
            'show_current_company' => $request->boolean('show_current_company'),
        ];

        session(['privacy_settings' => $settings]);

        return redirect()->route('settings.index')->with('success', 'Privacy settings updated!');
    }

    /**
     * Show delete account confirmation.
     */
    public function confirmDelete(): View
    {
        return view('settings.confirm-delete');
    }

    /**
     * Delete account.
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth()->user()->password)) {
                    $fail('The password is incorrect.');
                }
            }],
            'confirmation' => ['required', 'accepted'],
        ]);

        $user = auth()->user();
        
        // Logout the user
        auth()->logout();

        // Delete user's photo if exists
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Delete user's resume if exists
        if ($user->resume_path) {
            Storage::disk('public')->delete($user->resume_path);
        }

        // Delete the user
        $user->delete();

        return redirect('/')->with('success', 'Your account has been permanently deleted.');
    }
}