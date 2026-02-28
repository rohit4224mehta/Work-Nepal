<?php

namespace App\Providers;

use App\Mail\VerifyEmailCustom;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\URL;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Company::class        => \App\Policies\CompanyPolicy::class,
        \App\Models\JobPosting::class     => \App\Policies\JobPostingPolicy::class,
        \App\Models\JobApplication::class => \App\Policies\JobApplicationPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Full custom verification email override
        VerifyEmail::toMailUsing(function ($notifiable) {
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            // Pass user + URL to custom mailable
            return new VerifyEmailCustom($notifiable, $verificationUrl);
        });
    }
}