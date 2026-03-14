<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends AdminController
{
    /**
     * Display a listing of all users.
     */
    public function index(Request $request): View
    {
        $query = User::query();
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('mobile', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }
        
        $users = $query->latest()->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Display job seekers only.
     */
    public function jobSeekers(Request $request): View
    {
        $query = User::role('job_seeker');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        $users = $query->latest()->paginate(15);
        
        return view('admin.users.job-seekers', compact('users'));
    }
    
    /**
     * Display employers only.
     */
    public function employers(Request $request): View
    {
        $query = User::role('employer');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        $users = $query->latest()->paginate(15);
        
        return view('admin.users.employers', compact('users'));
    }
    
    /**
     * Show user details.
     */
    public function show(User $user): View
    {
        $user->load(['skills', 'education', 'experience', 'jobApplications' => function ($q) {
            $q->latest()->limit(5);
        }]);
        
        return view('admin.users.show', compact('user'));
    }
    
    /**
     * Suspend user account.
     */
    public function suspend(User $user)
    {
        $user->update(['account_status' => 'suspended']);
        
        $this->logAdminAction('user_suspended', "Suspended user: {$user->name} (ID: {$user->id})");
        
        return back()->with('success', 'User suspended successfully.');
    }
    
    /**
     * Activate user account.
     */
    public function activate(User $user)
    {
        $user->update(['account_status' => 'active']);
        
        $this->logAdminAction('user_activated', "Activated user: {$user->name} (ID: {$user->id})");
        
        return back()->with('success', 'User activated successfully.');
    }
    
    /**
     * Delete user account.
     */
    public function destroy(User $user)
    {
        $userName = $user->name;
        $userId = $user->id;
        
        $user->delete();
        
        $this->logAdminAction('user_deleted', "Deleted user: {$userName} (ID: {$userId})");
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}