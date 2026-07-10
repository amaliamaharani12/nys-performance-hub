<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProgressAchievementController;

Route::get('/progress-achievement', [ProgressAchievementController::class, 'index'])->name('progress-achievement');
Route::get('/progress-achievement/{metric}/detail', [ProgressAchievementController::class, 'detail'])->name('progress-achievement.detail');

Route::get('/', function () {
    return view('welcome');
});
