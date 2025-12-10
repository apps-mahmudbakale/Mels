<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Aspirant;
use Illuminate\Auth\Access\HandlesAuthorization;

class AspirantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_aspirant');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Aspirant $aspirant): bool
    {
        return $user->can('view_aspirant');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_aspirant');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Aspirant $aspirant): bool
    {
        return $user->can('update_aspirant');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Aspirant $aspirant): bool
    {
        return $user->can('delete_aspirant');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_aspirant');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Aspirant $aspirant): bool
    {
        return $user->can('force_delete_aspirant');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_aspirant');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Aspirant $aspirant): bool
    {
        return $user->can('restore_aspirant');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_aspirant');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Aspirant $aspirant): bool
    {
        return $user->can('replicate_aspirant');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_aspirant');
    }
}
