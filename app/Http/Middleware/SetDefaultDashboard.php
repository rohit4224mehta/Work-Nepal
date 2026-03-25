<?php
// app/Http/Middleware/SetDefaultDashboard.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetDefaultDashboard
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // If user has multiple roles and no active role is set, set default based on priority
            if ($user->roles->count() > 1 && !session()->has('active_role')) {
                $priorityRoles = ['super_admin', 'admin', 'employer', 'job_seeker'];
                foreach ($priorityRoles as $role) {
                    if ($user->hasRole($role)) {
                        session(['active_role' => $role]);
                        break;
                    }
                }
            }
        }
        
        return $next($request);
    }
}