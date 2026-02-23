<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class JobApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any applications (list).
     * Usually restricted — most users shouldn't see all applications.
     */
    public function viewAny(User $user): Response
    {
        // Platform admins can see everything
        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        // Regular users cannot see arbitrary application lists
        return Response::deny('You do not have permission to view application lists.');
    }

    /**
     * Determine whether the user can view a specific job application.
     */
    public function view(User $user, JobApplication $application): Response
    {
        // 1. The applicant themselves can always view their own application
        if ($application->job_seeker_id === $user->id) {
            return Response::allow();
        }

        // 2. Platform admin can view everything
        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        // 3. Company-side users who belong to the job's company
        $company = $application->jobPosting?->company;

        if (!$company) {
            return Response::denyAsNotFound();
        }

        // Must be active member of the company
        $isCompanyMember = $company->users()
            ->where('user_id', $user->id)
            ->wherePivot('is_active', true)
            ->exists();

        if (!$isCompanyMember) {
            return Response::deny('This application does not belong to your company.');
        }

        // At minimum, any active company member can view applications to their jobs
        // (you can make this stricter later if needed)
        return Response::allow();
    }

    /**
     * Determine whether the user can update the status of an application
     * (shortlist, reject, hire, etc.)
     */
    public function updateStatus(User $user, JobApplication $application): Response
    {
        // Platform admin can always moderate
        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        $company = $application->jobPosting?->company;

        if (!$company) {
            return Response::denyAsNotFound();
        }

        // Only owner / admin / recruiter of the company can change status
        $canManage = $company->users()
            ->where('user_id', $user->id)
            ->wherePivotIn('role', ['owner', 'admin', 'recruiter'])
            ->wherePivot('is_active', true)
            ->exists();

        if ($canManage) {
            return Response::allow();
        }

        return Response::deny('Only company owners, admins, or recruiters can update application status.');
    }

    /**
     * Determine whether the user can download the CV attached to the application.
     */
    public function downloadCv(User $user, JobApplication $application): Response
    {
        // Same rules as view — applicant + company authorized members + admin
        if ($application->job_seeker_id === $user->id) {
            return Response::allow();
        }

        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        $company = $application->jobPosting?->company;

        if (!$company) {
            return Response::denyAsNotFound();
        }

        $isAuthorized = $company->users()
            ->where('user_id', $user->id)
            ->wherePivotIn('role', ['owner', 'admin', 'recruiter'])
            ->wherePivot('is_active', true)
            ->exists();

        if ($isAuthorized) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to download this CV.');
    }

    /**
     * Determine whether the user can delete / withdraw their own application.
     */
    public function delete(User $user, JobApplication $application): Response
    {
        // Job seeker can withdraw their own application (before it's processed)
        if ($application->job_seeker_id === $user->id) {
            // Optional: prevent withdrawal after certain statuses
            if (in_array($application->status, ['shortlisted', 'hired'])) {
                return Response::deny('You cannot withdraw an application that has been shortlisted or hired.');
            }

            return Response::allow();
        }

        // Company side can "reject" but not delete — use updateStatus instead
        // Only admin can force delete if needed
        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        return Response::deny('You can only withdraw your own application.');
    }

    /**
     * Determine whether the user can add feedback / notes to the application.
     */
    public function addFeedback(User $user, JobApplication $application): Response
    {
        // Typically only company-side authorized users
        $company = $application->jobPosting?->company;

        if (!$company) {
            return Response::denyAsNotFound();
        }

        $canProvideFeedback = $company->users()
            ->where('user_id', $user->id)
            ->wherePivotIn('role', ['owner', 'admin', 'recruiter'])
            ->wherePivot('is_active', true)
            ->exists();

        if ($canProvideFeedback) {
            return Response::allow();
        }

        return Response::deny('Only company recruiters, admins, or owners can add feedback.');
    }
}