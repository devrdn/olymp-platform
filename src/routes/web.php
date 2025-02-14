<?php

use App\Contracts\PermissionCheckerInterface;
use App\Enums\Roles;
use App\Models\Contest;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

Route::get('/', function () {
    return 'Hello, World';
});
