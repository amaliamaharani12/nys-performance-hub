<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProgressAchievementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\PicController;
use App\Http\Controllers\Admin\DataReviewController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Pic\ProfileController;
use App\Http\Controllers\Pic\DataEntryController;
use App\Http\Controllers\Pic\UploadController;

// Public
Route::get('/progress-achievement', [ProgressAchievementController::class, 'index'])->name('progress-achievement');
Route::get('/progress-achievement/{metric}/detail', [ProgressAchievementController::class, 'detail'])->name('progress-achievement.detail');

Route::get('/', function () {
    return redirect('/login');
});

// Auth
Route::get('/login', [LoginController::class, 'create'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'store'])->name('login.store')->middleware('guest');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');

// ─── Admin ──────────────────────────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Kelola Akun PIC
    Route::get('/pic', [PicController::class, 'index'])->name('pic.index');
    Route::get('/pic/create', [PicController::class, 'create'])->name('pic.create');
    Route::post('/pic', [PicController::class, 'store'])->name('pic.store');
    Route::get('/pic/{pic}/edit', [PicController::class, 'edit'])->name('pic.edit');
    Route::put('/pic/{pic}', [PicController::class, 'update'])->name('pic.update');
    Route::patch('/pic/{pic}/toggle-aktif', [PicController::class, 'toggleAktif'])->name('pic.toggle-aktif');
    Route::delete('/pic/{pic}', [PicController::class, 'destroy'])->name('pic.destroy');

    // Review & Revisi Data Actual
    Route::get('/data', [DataReviewController::class, 'index'])->name('data.index');
    Route::patch('/data/{actual}/approve', [DataReviewController::class, 'approve'])->name('data.approve');
    Route::get('/data/{actual}/revise', [DataReviewController::class, 'revise'])->name('data.revise');
    Route::patch('/data/{actual}/revise', [DataReviewController::class, 'storeRevision'])->name('data.revise.store');
    Route::patch('/data/{actual}/reject', [DataReviewController::class, 'reject'])->name('data.reject');

    // Manage Groups
    Route::get('/group',              [GroupController::class, 'index'])->name('group.index');
    Route::get('/group/create',       [GroupController::class, 'create'])->name('group.create');
    Route::post('/group',             [GroupController::class, 'store'])->name('group.store');
    Route::get('/group/{group}/edit', [GroupController::class, 'edit'])->name('group.edit');
    Route::put('/group/{group}',      [GroupController::class, 'update'])->name('group.update');
    Route::delete('/group/{group}',   [GroupController::class, 'destroy'])->name('group.destroy');
});

// ─── PIC ────────────────────────────────────────────────────────────────────
Route::prefix('pic')->middleware(['auth', 'role:pic'])->name('pic.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $user    = Auth::user()->load('group');
        $total   = \App\Models\Actual::where('input_by', $user->id)->count();
        $pending = \App\Models\Actual::where('input_by', $user->id)->where('status', 'pending')->count();
        $approved= \App\Models\Actual::where('input_by', $user->id)->where('status', 'approved')->count();
        $draft   = \App\Models\Actual::where('input_by', $user->id)->where('status', 'draft')->count();
        $recent  = \App\Models\Actual::with('metric')
                    ->where('input_by', $user->id)
                    ->orderByDesc('created_at')
                    ->limit(5)
                    ->get();
        return view('pic.dashboard', compact('total', 'pending', 'approved', 'draft', 'recent'));
    })->name('dashboard');

    // Edit Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Data Entry
    Route::get('/data',              [DataEntryController::class, 'index'])->name('data.index');
    Route::get('/data/create',       [DataEntryController::class, 'create'])->name('data.create');
    Route::post('/data',             [DataEntryController::class, 'store'])->name('data.store');
    Route::get('/data/{actual}/edit',[DataEntryController::class, 'edit'])->name('data.edit');
    Route::put('/data/{actual}',     [DataEntryController::class, 'update'])->name('data.update');
    Route::delete('/data/{actual}',  [DataEntryController::class, 'destroy'])->name('data.destroy');

    // CSV Upload
    Route::get('/upload',          [UploadController::class, 'index'])->name('upload.index');
    Route::post('/upload',         [UploadController::class, 'store'])->name('upload.store');
    Route::get('/upload/template', [UploadController::class, 'downloadTemplate'])->name('upload.template');
});