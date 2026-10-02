<?php

use App\Http\Controllers\ProfileController;
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


//Route::get('/', [\App\Http\Controllers\Client\PostController::class, 'index'])->name('post.index');

Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/like', [\App\Http\Controllers\LikeController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/unlike', [\App\Http\Controllers\LikeController::class, 'unlike'])->name('posts.unlike');
    Route::post('/posts/{post}/toggle-like', [\App\Http\Controllers\LikeController::class, 'toggleLike'])->name('posts.like.toggle');

    Route::get('/violations/{violation}/appeal', [\App\Http\Controllers\Client\ViolationController::class, 'create'])->name('profile.violations.appeal');
});



require __DIR__.'/admin.php';
require __DIR__.'/client.php';
require __DIR__.'/auth.php';
