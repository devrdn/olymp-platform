<?php

namespace App\Services;

use App\Contracts\PermissionCheckerInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User;

/**
 * Class PermissionChecker
 * 
 * This class is responsible for checking if a user has a specific permission.
 * It implements the PermissionCheckerInterface.
 * 
 * @package App\Services
 */
class PermissionChecker implements PermissionCheckerInterface
{
    /**
     * Check if the user has the specified permission.
     *
     * This method first checks if the user has the given permission directly.
     * If not, and a contest ID is provided, it checks if the user has any roles
     * associated with the given contest that grant the specified permission.
     *
     * @param string $permission The permission to check.
     * @param int|null $contestId The ID of the contest to check roles for, or null if not applicable.
     * 
     * @return bool True if the user has the permission, false otherwise.
     */
    public function hasPermission(?User $user, string $permission, ?int $contestId = null): bool
    {
        if (!$user) {
            return false;
        }

        if ($user->hasPermissionTo($permission) && !$contestId) {
            return true;
        }

        if (!$contestId) {
            return false;
        }

        $roles = $user->roles()
            ->wherePivot('contest_id', $contestId)
            ->with('permissions')
            ->get();

        // Check if the user has any roles that grant the specified permission.
        foreach ($roles as $role) {
            if ($role->permissions->pluck('name')->contains($permission)) {
                return true;
            }
        }

        return false;
    }
}
