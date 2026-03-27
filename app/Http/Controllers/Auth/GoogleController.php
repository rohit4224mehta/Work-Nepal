<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // 1. Check existing social account
            $socialAccount = SocialAccount::where('provider', 'google')
                ->where('provider_id', $googleUser->getId())
                ->first();

            if ($socialAccount) {
                Auth::login($socialAccount->user);
                return redirect()->route('dashboard');
            }

            // 2. Check existing user by email
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(), // 🔥 auto verified
                ]);
            } else {
                // Auto verify existing user
                if (!$user->email_verified_at) {
                    $user->update([
                        'email_verified_at' => now()
                    ]);
                }
            }

            // 3. Create social account
            SocialAccount::create([
                'user_id' => $user->id,
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'avatar' => $googleUser->getAvatar(),
                'raw' => json_encode($googleUser),
            ]);

            // 4. Login
            Auth::login($user);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google login failed');
        }
    }
}