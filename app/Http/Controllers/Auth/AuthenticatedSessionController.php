<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Get the authenticated user
        $user = $request->user();

        // 1. Check for "intended" redirect (from Apply / Post Job buttons)
        if ($request->session()->has('url.intended')) {
            return redirect()->intended();
        }

        // 2. Role-based redirection (your decision matrix)
        if ($user->hasRole('admin') || $user->hasRole('super_admin')) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome back, Administrator!');
        }

        if ($user->hasRole('employer')) {
            // Optional: if no company → force create company
            if (!$user->companies()->exists()) {
                return redirect()->route('employer.company.create')
                    ->with('info', 'Please create your company profile first.');
            }

            return redirect()->route('employer.dashboard')
                ->with('success', 'Welcome back to your employer dashboard!');
        }

        // 3. Default: job seeker (including new registrations)
        return redirect()->route('dashboard.jobseeker')
            ->with('success', 'Welcome back! Check new job recommendations.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}