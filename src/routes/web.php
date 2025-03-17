<?php

use App\Http\Controllers\ContestController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('contest', ContestController::class)
    ->whereNumber('contest');

require __DIR__ . '/auth.php';
