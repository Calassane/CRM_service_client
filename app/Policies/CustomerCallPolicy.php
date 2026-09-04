<?php

namespace App\Policies;

use App\Models\CustomerCall;
use App\Models\User;

class CustomerCallPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CustomerCall $customerCall): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CustomerCall $customerCall): bool
    {
        return $user->is($customerCall->agent);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CustomerCall $customerCall): bool
    {
        return $user->is($customerCall->agent);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CustomerCall $customerCall): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CustomerCall $customerCall): bool
    {
        return false;
    }
}
