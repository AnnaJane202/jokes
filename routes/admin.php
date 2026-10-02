<?php

use App\Http\Controllers\Admin\ViolationController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});
//
//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
//
//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});


Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard')->prefix('admin')->middleware(['auth', IsAdminMiddleware::class]);

Route::prefix('admin')->name('admin.')->middleware(['auth', IsAdminMiddleware::class])->group(function(){

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('comments', \App\Http\Controllers\Admin\CommentController::class)->only(['index', 'edit', 'update', 'destroy']);

    Route::get('users/ban/create', [\App\Http\Controllers\Admin\UserBanController::class, 'create'])->name('ban.create');
    Route::post('users/{user}/ban', [\App\Http\Controllers\Admin\UserBanController::class, 'store'])->name('ban.store');
    Route::post('users/{user}/unban', [\App\Http\Controllers\Admin\UserBanController::class, 'destroy'])->name('ban.destroy');
    Route::post('users/banned', [\App\Http\Controllers\Admin\UserBanController::class, 'index'])->name('ban.index');

    Route::get('/violations/create/{user}', [\App\Http\Controllers\Admin\ViolationController::class, 'create'])->name('violations.create');
    Route::post('violations', [\App\Http\Controllers\Admin\ViolationController::class, 'store'])->name('violations.store');
    Route::get('violations', [\App\Http\Controllers\Admin\ViolationController::class, 'index'])->name('violations.index');
    Route::get('/users/{user}/violations', [ViolationController::class, 'userHistory'])->name('users.violations');

    Route::post('violations/{violation}/review-appeal', [ViolationController::class, 'reviewAppealForm'])->name('violations.review-appeal');

    Route::post('/appeals/{violation}/quick-approve', [ViolationController::class, 'quickApprove'])
        ->name('appeals.quick-approve');
    Route::post('/appeals/{violation}/quick-reject', [ViolationController::class, 'quickReject'])
        ->name('appeals.quick-reject');
    Route::get('/appeals/{violation}/review', [ViolationController::class, 'review'])
        ->name('appeals.review');
    Route::post('/appeals/{violation}/review', [ViolationController::class, 'processReview'])
        ->name('appeals.process-review');

    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{report}/resolve', [\App\Http\Controllers\Admin\ReportController::class, 'resolve'])->name('reports.resolve');




});


