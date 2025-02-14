<?php

namespace Database\Seeders;

use App\Enums\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'auth.login',
            'auth.logout',
            'contest.list.view',
            'contest.details.view',
            'contest.create',
            'contest.edit',
            'contest.task.add',
            'contest.enroll.self',
            'contest.enroll.other',
            'contest.delete',
            'user.list.view',
            'user.details.view',
            'user.create',
            'user.edit',
            'user.delete',
            'role.list.view',
            'role.assign',
            'task.private.create',
            'task.public.publish',
            'task.private.view',
            'task.public.view',
            'task.details.view',
            'task.edit',
            'task.delete',
            'task.submit',
            'submission.list.view',
            'submission.details.view',
            'ranking.view',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $guest             = Role::firstOrCreate(['name' => Roles::GUEST]);
        $authUser          = Role::firstOrCreate(['name' => Roles::AUTHENTICATED_USER]);
        $admin             = Role::firstOrCreate(['name' => Roles::ADMIN]);
        $contestant        = Role::firstOrCreate(['name' => Roles::CONTESTANT]);
        $contestManager    = Role::firstOrCreate(['name' => Roles::CONTEST_MANAGER]);

        $guest->syncPermissions([
            'auth.login',
            'contest.list.view',
            'contest.details.view',
            'ranking.view',
        ]);

        $authUser->syncPermissions([
            'auth.logout',
            'contest.list.view',
            'contest.details.view',
            'contest.enroll.self',
            'ranking.view',
        ]);

        $admin->syncPermissions([
            'auth.logout',
            'contest.list.view',
            'contest.details.view',
            'contest.create',
            'contest.edit',
            'contest.task.add',
            'contest.enroll.other',
            'contest.delete',
            'user.list.view',
            'user.details.view',
            'user.create',
            'user.edit',
            'user.delete',
            'role.list.view',
            'role.assign',
            'task.private.create',
            'task.public.publish',
            'task.private.view',
            'task.public.view',
            'task.details.view',
            'task.edit',
            'task.delete',
            'submission.list.view',
            'submission.details.view',
            'ranking.view',
        ]);

        $contestant->syncPermissions([
            'auth.logout',
            'contest.list.view',
            'contest.details.view',
            'task.details.view',
            'task.submit',
            'ranking.view',
        ]);

        $contestManager->syncPermissions([
            'auth.logout',
            'contest.list.view',
            'contest.details.view',
            'contest.edit',
            'contest.task.add',
            'contest.enroll.other',
            'contest.delete',
            'user.list.view',
            'user.details.view',
            'task.private.create',
            'task.public.publish',
            'task.private.view',
            'task.public.view',
            'task.details.view',
            'task.edit',
            'task.delete',
            'submission.list.view',
            'submission.details.view',
            'ranking.view',
        ]);
    }
}
