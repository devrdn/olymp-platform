<?php

use App\Http\Controllers\ContestController;
use App\Http\Controllers\ContestTaskController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('contest', ContestController::class)
    ->whereNumber('contest');

Route::resource('contest.task', ContestTaskController::class)
    ->whereNumber('contest')
    ->only(['index', 'show', 'store', 'destroy'])
    ->scoped();

Route::post('/contest/{contest}/task/{task}/submit', [ContestTaskController::class, 'submit'])
    ->name('contest.task.submit')
    ->whereNumber('contest')
    ->whereNumber('task');

require __DIR__ . '/auth.php';
