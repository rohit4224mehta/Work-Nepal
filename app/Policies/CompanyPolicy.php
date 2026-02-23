<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any companies (list/index).
     */
    public function viewAny(User $user): bool
    {
        // Everyone can see the public company list (for job seekers browsing employers)
        return true;
    }

    /**
     * Determine whether the user can view a specific company.
     */
    public function view(User $user, Company $company): bool
    {
        // Public viewing is allowed (company profiles are visible)
        return true;
    }

    /**
     * Determine whether the user can create companies.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create a company (to become an employer)
        return $user->exists;
    }

    /**
     * Determine whether the user can update the company.
     */
    public function update(User $user, Company $company): Response
    {
        // Owner can always update
        if ($company->owner_id === $user->id) {
            return Response::allow();
        }

        // Company members with admin/recruiter role can also update
        $canManage = $company->users()
            ->where('user_id', $user->id)
            ->wherePivotIn('role', ['admin', 'owner', 'recruiter'])
            ->wherePivot('is_active', true)
            ->exists();

        if ($canManage) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to edit this company.');
    }

    /**
     * Determine whether the user can delete the company.
     */
    public function delete(User $user, Company $company): Response
    {
        // Only the owner can delete the company
        if ($company->owner_id === $user->id) {
            return Response::allow();
        }

        return Response::deny('Only the company owner can delete this company.');
    }

    /**
     * Determine whether the user can manage members (add/remove users, change roles).
     */
    public function manageMembers(User $user, Company $company): Response
    {
        // Owner or company admin can manage members
        if ($company->owner_id === $user->id) {
            return Response::allow();
        }

        $isAdmin = $company->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'admin')
            ->wherePivot('is_active', true)
            ->exists();

        if ($isAdmin) {
            return Response::allow();
        }

        return Response::deny('You do not have permission to manage company members.');
    }

    /**
     * Determine whether the user can verify companies (admin only).
     */
    public function verify(User $user, Company $company): Response
    {
        // Only platform admins can verify/reject companies
        if ($user->hasRole('admin')) {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can view the company's dashboard/statistics.
     */
    public function viewDashboard(User $user, Company $company): Response
    {
        // Owner or active members with recruiter/admin role
        if ($company->owner_id === $user->id) {
            return Response::allow();
        }

        $canView = $company->users()
            ->where('user_id', $user->id)
            ->wherePivotIn('role', ['admin', 'recruiter', 'owner'])
            ->wherePivot('is_active', true)
            ->exists();

        if ($canView) {
            return Response::allow();
        }

        return Response::deny('You do not have access to this company dashboard.');
    }
}