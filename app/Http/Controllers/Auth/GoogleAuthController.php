<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
{
    $googleUser = Socialite::driver('google')->user();

    $user = User::firstOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name'              => $googleUser->getName(),
            'google_id'         => $googleUser->getId(),
            'profile_photo_path'=> $googleUser->getAvatar(),
            'email_verified_at' => now(),
            'password'          => bcrypt(Str::random(16)),
        ]
    );

    // Attach social account
    $user->socialAccounts()->updateOrCreate(
        ['provider' => 'google', 'provider_id' => $googleUser->getId()],
        [
            'email'  => $googleUser->getEmail(),
            'name'   => $googleUser->getName(),
            'avatar' => $googleUser->getAvatar(),
            'raw'    => $googleUser->user,
        ]
    );

    // Default global role
    if (!$user->hasAnyRole(['job_seeker', 'employer', 'admin'])) {
        $user->assignRole('job_seeker');
    }

    Auth::login($user);

    // For new users → ask them to complete profile or choose "Become Employer"
    if (!$user->profile_completed) { // add boolean column later
        return redirect('/profile/onboarding');
    }

    return redirect($this->redirectPathForUser($user));
}

    protected function redirectToDashboard(User $user)
    {
        if ($user->hasRole('admin')) {
            return '/admin/dashboard';
        } elseif ($user->hasRole('employer')) {
            return '/employer/dashboard';
        } else {
            return '/dashboard'; // job seeker
        }
    }
}