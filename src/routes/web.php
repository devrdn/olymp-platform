<?php

use App\Contracts\PermissionCheckerInterface;
use App\Enums\Roles;
use App\Models\Contest;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

Route::get('/', function () {

    $user = User::first();

    // $user->roles()->attach(
    //     Role::where('name', Roles::CONTESTANT)->first(),
    //     ['contest_id' => 1]
    // );

    // $user->roles()->attach(
    //     Role::where('name', Roles::CONTEST_MANAGER)->first(),
    //     ['contest_id' => 2]
    // );

    dd(
        $user->can('task.submit', ['contestId' => 1]),
        $user->can('task.submit', ['contestId' => 2]),
        $user->can('task.private.create', ['contestId' => 1]),
        $user->can('task.private.create', ['contestId' => 2])
    );

    return 'Hello, World';
});
