<?php

use App\Http\Controllers\Client\CommentController;
use App\Http\Controllers\Client\ReportController;
use App\Http\Controllers\Client\ReportEvidenceController;
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
// Страница бана (доступна только авторизованным)
Route::get('/banned', [\App\Http\Controllers\Client\BanController::class, 'index'])
    ->name('client.banned.notice');

// Апелляции (доступны даже забаненным)
Route::name('client.')->middleware(['auth'])->group(function () {
    Route::post('/appeal/upload-evidence', [\App\Http\Controllers\Client\ViolationController::class, 'uploadAppealEvidence'])->name('appeal.appeal-evidence');

    Route::get('/violations', [\App\Http\Controllers\Client\ViolationController::class, 'index'])->name('violations.index');
    Route::get('/violations/{violation}', [\App\Http\Controllers\Client\ViolationController::class, 'show'])->name('violations.show');
    Route::get('/violations/{violation}/appeal', [\App\Http\Controllers\Client\ViolationController::class, 'create'])->name('violations.appeal');
    Route::post('/violations/{violation}/appeal/store', [\App\Http\Controllers\Client\ViolationController::class, 'store'])->name('violations.appeal.store');
    Route::get('/appeals', [\App\Http\Controllers\Client\ViolationController::class, 'myAppeals'])->name('appeals.index');



//    Route::get('/appeals', [::class, 'myAppeals'])->name('appeals.index');
});




Route::name('client.')->middleware(['auth', 'check.banned'])->group(function () {
    Route::get('/posts/create', [\App\Http\Controllers\Client\PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [\App\Http\Controllers\Client\PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [\App\Http\Controllers\Client\PostController::class, 'show'])->name('posts.show');
    Route::get('/posts', [\App\Http\Controllers\Client\PostController::class, 'myPosts'])->name('profile.posts.index');
    Route::delete('/{post}', [\App\Http\Controllers\Client\PostController::class, 'destroy'])->name('posts.destroy');
    Route::put('/posts/{post}/comments/{comment}', [CommentController::class, 'update'])->name('posts.comments.update');

    Route::get('/reports/create/{type}/{id}', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports/store', [ReportController::class, 'store'])->name('reports.store');
    Route::post('/reports/upload-evidence', [ReportEvidenceController::class, 'upload'])->name('reports.upload-evidence');
    Route::delete('/reports/evidence/{id}', [ReportEvidenceController::class, 'delete'])->name('reports.evidence.delete');
    Route::get('/reports/index', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');

    Route::get('/profile/edit', [\App\Http\Controllers\Client\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\Client\ProfileController::class, 'update'])->name('profile.update');

    Route::post('/profile/avatar', [\App\Http\Controllers\Client\ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store')->scopeBindings();;
    Route::put('/posts/{post}/comments/{comment}', [CommentController::class, 'update'])->name('posts.comments.update')->scopeBindings();;
    Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('posts.comments.destroy')->scopeBindings();;

    Route::get('/categories/{category:slug}', [\App\Http\Controllers\Client\CategoryController::class, 'show'])
        ->name('categories.show');

    Route::get('/profile', [\App\Http\Controllers\Client\ProfileController::class, 'dashboard'])->name('profile.dashboard');
//    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
//    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
//    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});

//Route::post('/upload-evidence', [ReportEvidenceController::class, 'upload'])->name('upload-evidence');
//Route::delete('/evidence/{id}', [ReportEvidenceController::class, 'delete'])->name('evidence.delete');

Route::middleware('check.banned')->group(function () {
    Route::get('/', [\App\Http\Controllers\Client\PostController::class, 'index'])->name('post.index');

    Route::get('/users', [\App\Http\Controllers\Client\UserController::class, 'index'])->name('client.users.index');
    Route::get('/users/{user}', [\App\Http\Controllers\Client\UserController::class, 'show'])->name('client.users.show');
});






//Route::name('client.')->middleware('auth')->group(function () {
//    //Route::get('products', [\App\Http\Controllers\Client\ProductController::class, 'index'])->name('products.index');
//    Route::get('products/{product}', [\App\Http\Controllers\Client\ProductController::class, 'show'])->name('products.show');
//    Route::get('categories', [\App\Http\Controllers\Client\CategoryController::class, 'index'])->name('categories.index');
//    Route::get('categories/{category}/products', [\App\Http\Controllers\Client\CategoryController::class, 'productIndex'])->name('categories.products.index');
//
//    Route::resource('/carts', \App\Http\Controllers\Client\CartController::class);
//    Route::post('/orders', [\App\Http\Controllers\Client\OrderController::class, 'store'])->name('orders.store');
//    Route::get('/orders/{order}/transactions/create', [\App\Http\Controllers\Client\OrderController::class, 'createTransaction'])->name('orders.transactions.create');
//});

//require __DIR__.'/auth.php';
