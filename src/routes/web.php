<?php

use App\Contracts\PermissionCheckerInterface;
use App\Models\Contest;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Hello, World';
});
