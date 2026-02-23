<?php

namespace App\Policies;

use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class JobPostingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any job postings (public list).
     */
    public function viewAny(User $user): bool
    {
        // Everyone can see public job listings
        return true;
    }

    /**
     * Determine whether the user can view a specific job posting.
     */
    public function view(User $user, JobPosting $jobPosting): bool
    {
        // All job postings are public once approved
        return true;
    }

    /**
     * Determine whether the user can create a new job posting.
     */
    public function create(User $user): Response
    {
        // Must be an employer (has at least one company)
        if (!$user->isEmployer()) {
            return Response::deny('You must create or join a company to post jobs.');
        }

        // Optionally: check if they have active company membership
        $hasActiveCompany = $user->companies()
            ->wherePivot('is_active', true)
            ->exists();

        if (!$hasActiveCompany) {
            return Response::deny('You need an active company membership to post jobs.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update a job posting.
     */
    public function update(User $user, JobPosting $jobPosting): Response
    {
        // Must belong to the same company
        $company = $jobPosting->company;

        if (!$company) {
            return Response::denyAsNotFound();
        }

        // Owner of the company
        if ($company->owner_id === $user->id) {
            return Response::allow();
        }

        // Company members with permission to manage jobs
        $canManage = $company->users()
            ->where('user_id', $user->id)
            ->wherePivotIn('role', ['admin', 'recruiter', 'owner'])
            ->wherePivot('is_active', true)
            ->exists();

        if ($canManage) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to edit this job posting.');
    }

    /**
     * Determine whether the user can delete a job posting.
     */
    public function delete(User $user, JobPosting $jobPosting): Response
    {
        // Same rules as update — only owner or authorized company members
        $company = $jobPosting->company;

        if (!$company) {
            return Response::denyAsNotFound();
        }

        if ($company->owner_id === $user->id) {
            return Response::allow();
        }

        $canDelete = $company->users()
            ->where('user_id', $user->id)
            ->wherePivotIn('role', ['admin', 'owner'])
            ->wherePivot('is_active', true)
            ->exists();

        if ($canDelete) {
            return Response::allow();
        }

        return Response::deny('Only company owners or admins can delete job postings.');
    }

    /**
     * Determine whether the user can manage applications for this job.
     */
    public function manageApplications(User $user, JobPosting $jobPosting): Response
    {
        $company = $jobPosting->company;

        if (!$company) {
            return Response::denyAsNotFound();
        }

        // Owner or recruiter/admin role in the company
        if ($company->owner_id === $user->id) {
            return Response::allow();
        }

        $canManage = $company->users()
            ->where('user_id', $user->id)
            ->wherePivotIn('role', ['admin', 'recruiter'])
            ->wherePivot('is_active', true)
            ->exists();

        if ($canManage) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to manage applications for this job.');
    }

    /**
     * Determine whether platform admin can moderate/approve this job posting.
     */
    public function moderate(User $user, JobPosting $jobPosting): Response
    {
        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    }
}