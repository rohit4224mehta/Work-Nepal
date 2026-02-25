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

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'mobile'            => ['nullable', 'string', 'max:20', 'unique:'.User::class.',mobile'],
            'password'          => ['required', 'confirmed', Rules\Password::defaults()],
            'gender'            => [
                'nullable',
                'string',
                'in:' . implode(',', array_column(Gender::cases(), 'value'))
            ],
            'date_of_birth'     => ['nullable', 'date', 'before:-18 years'],
            'terms'             => ['required', 'accepted'],
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'mobile'            => $request->mobile,
            'password'          => Hash::make($request->password),
            'gender'            => $request->gender ? Gender::from($request->gender) : null,
            'date_of_birth'     => $request->date_of_birth,
            'account_status'    => AccountStatus::ACTIVE,
        ]);

        // Auto-assign default role
        $user->assignRole('job_seeker');

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}