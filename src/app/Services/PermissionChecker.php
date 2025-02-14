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
     * @param User|null $user The user to check the permission for.
     * @param string $permission The permission to check.
     * @param int|null $contestId The contest ID to check the permission within (optional).
     * 
     * @return bool True if the user has the specified permission, false otherwise.
     */
    public function hasPermission(?User $user, string $permission, ?int $contestId): bool
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
