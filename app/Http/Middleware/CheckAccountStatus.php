<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Support\Enums\AccountStatus;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->account_status === AccountStatus::SUSPENDED) {
                Auth::logout();

                return redirect()
                    ->route('login')
                    ->with('error', 'Your account has been suspended. Please contact support.');
            }

            // Optional: you can also check deleted here, but soft-deletes usually block login anyway
        }

        return $next($request);
    }
}