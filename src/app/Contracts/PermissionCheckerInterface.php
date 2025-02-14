<?php

namespace App\Contracts;

use App\Models\User;

interface PermissionCheckerInterface
{
    /**
     * Check if the user has the given permission.
     * 
     * @param User $user
     * @param string $permission The permission to check (e.g 'contest.create')
     * @param int|null $contestId The contest ID to check the permission for
     * 
     * @return bool
     */
    public function hasPermission(User $user, string $permission, ?int $contestId): bool;
}
