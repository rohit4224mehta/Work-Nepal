<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Already verified → redirect to dashboard
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard.jobseeker'))
                ->with('status', 'Your email is already verified.');
        }

        // Mark as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        } else {
            // Rare failure case → log and redirect with error
            \Log::warning('Failed to mark email as verified for user ID: ' . $user->id);
            return redirect()->route('verification.notice')
                ->with('error', 'Failed to verify email. Please try again or contact support.');
        }

        // Re-login with remember me = true (fixes logout issue)
        Auth::login($user, true);

        return redirect()->intended(route('dashboard.jobseeker'))
            ->with('status', 'Your email has been verified! Welcome to WorkNepal.');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Already verified → no need to resend
        if ($user->hasVerifiedEmail()) {
            return back()->with('status', 'Your email is already verified.');
        }

        // Send fresh verification email
        $user->sendEmailVerificationNotification();

        return back()->with('status', 'A new verification link has been sent to your email!');
    }
}