<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Support\Enums\AccountStatus;
use App\Support\Enums\Gender;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'              => ['required', 'string', 'max:255', 'min:2'],
            'email'             => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'mobile'            => ['nullable', 'string', 'max:20', 'regex:/^[0-9]{10}$/', 'unique:'.User::class.',mobile'],
            'password'          => ['required', 'confirmed', Rules\Password::defaults()->min(8)->mixedCase()->numbers()],
            'gender'            => ['nullable', Rule::in(array_column(Gender::cases(), 'value'))],
            'date_of_birth'     => ['nullable', 'date', 'before:-18 years'],
            'terms'             => ['required', 'accepted'],
        ], [
            'date_of_birth.before' => 'You must be at least 18 years old to register.',
            'mobile.regex'         => 'Please enter a valid 10-digit Nepali mobile number.',
            'terms.accepted'       => 'You must accept the Terms of Service and Privacy Policy.',
        ]);

        $user = User::create([
            'name'              => trim($request->name),
            'email'             => $request->email,
            'mobile'            => $request->mobile,
            'password'          => Hash::make($request->password),
            'gender'            => $request->gender ? Gender::from($request->gender) : null,
            'date_of_birth'     => $request->date_of_birth,
            'account_status'    => AccountStatus::ACTIVE,
        ]);

        // ─── Auto-assign default role ────────────────────────────────────────
        $user->assignRole('job_seeker');

        // ─── Fire registration event (sends verification email) ───────────────
        event(new Registered($user));

        // ─── Log the user in immediately ─────────────────────────────────────
        Auth::login($user);

        // ─── Redirect to dashboard (or profile completion if needed) ─────────
        return redirect()->route('dashboard.jobseeker')
            ->with('success', 'Registration successful! Welcome to WorkNepal.');
    }
}