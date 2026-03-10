<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Education;

class EducationPolicy
{
    /**
     * Determine whether the user can view any education records.
     *
     * Typically allowed for authenticated users.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view a specific education record.
     *
     * Only the owner of the education entry can view it.
     */
    public function view(User $user, Education $education): bool
    {
        return $user->id === $education->user_id;
    }

    /**
     * Determine whether the user can create education records.
     *
     * Any authenticated user can add education to their profile.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the education record.
     *
     * Only the owner of the education entry can update it.
     */
    public function update(User $user, Education $education): bool
    {
        return $user->id === $education->user_id;
    }

    /**
     * Determine whether the user can delete the education record.
     *
     * Only the owner of the education entry can delete it.
     */
    public function delete(User $user, Education $education): bool
    {
        return $user->id === $education->user_id;
    }

    /**
     * Determine whether the user can restore the education record.
     *
     * Used if soft deletes are enabled.
     */
    public function restore(User $user, Education $education): bool
    {
        return $user->id === $education->user_id;
    }

    /**
     * Determine whether the user can permanently delete the education record.
     *
     * Usually restricted to admins, but here limited to owner.
     */
    public function forceDelete(User $user, Education $education): bool
    {
        return $user->id === $education->user_id;
    }
}