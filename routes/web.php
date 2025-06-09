<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Requests\LoginRequest;
use App\Models\Post;
use App\Models\Category;
use app\Models\User;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ModeratorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    $categories = Category::paginate(20);
    return view('welcome', compact('categories'));
});


Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::resource('posts', PostController::class);
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/profile', function ()
{
    $categories = Category::paginate(20);
    return view('profile', ['title' => 'Profile', 'categories' => $categories]);
});
Route::get('/reputation', function ()
{
    $categories = Category::paginate(20);
    return view('reputation', ['title' => 'Reputation', 'categories' => $categories]);
});


// Comment routes
Route::middleware(['auth'])->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::match(['put', 'post'], '/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/vote', [CommentController::class, 'vote']);
    Route::post('/report/{type}/{id}', [ReportController::class, 'store']);
});

Route::get('/asker/{user:username}', function(User $user){
    return view('posts', ['title' => count($user->posts) . ' Buku dari ' . $user->name, 'bukus' => $user->posts]);
});
Route::get('/categories/{category:slug}', function(Category $category){
    return view('posts', ['title' => 'Post Kategori ' . $category->name, 'posts' => $category->posts]);
});

Route::middleware('auth')->group(function () {
    Route::put('/profile', function(UpdateProfileRequest $request) {
        $request->persist();
        return back()->with('success', 'Profil berhasil diupdate!');
    })->name('profile.update');

    Route::put('/profile/password', function(UpdatePasswordRequest $request) {
        $request->persist();
        return back()->with('success', 'Password berhasil diubah!');
    })->name('profile.password');
});   

Route::middleware(['auth', 'can:moderate'])->prefix('moderator')->group(function () {
    Route::get('/reports', [ModeratorController::class, 'reports'])->name('moderator.reports');
    Route::delete('/report/{id}', [ModeratorController::class, 'deleteReport'])->name('moderator.deleteReport');
    Route::delete('/content/{type}/{id}', [ModeratorController::class, 'deleteContent'])->name('moderator.deleteContent');
    Route::post('/content/{type}/{id}/edit', [ModeratorController::class, 'editContent'])->name('moderator.editContent');
    Route::post('/close-post/{post}', [ModeratorController::class, 'closePost'])->name('moderator.closePost');
    Route::delete('/delete-comment/{comment}', [ModeratorController::class, 'deleteComment'])->name('moderator.deleteComment');
    Route::delete('/delete-post/{post}', [ModeratorController::class, 'deletePost'])->name('moderator.deletePost');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Hapus user
    Route::delete('/user/{user}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    // Hapus kategori
    Route::delete('/category/{category}', [AdminController::class, 'deleteCategory'])->name('admin.deleteCategory');
    // Halaman manajemen user
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    // Halaman manajemen post
    Route::get('/posts', [AdminController::class, 'posts'])->name('admin.posts');
    // Halaman manajemen kategori
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    // Hapus post
    Route::delete('/post/{post}', [AdminController::class, 'deletePost'])->name('admin.deletePost');
    // Halaman manajemen komentar
    Route::get('/comments', [AdminController::class, 'comments'])->name('admin.comments');
    // Hapus komentar
    Route::delete('/comment/{comment}', [AdminController::class, 'deleteComment'])->name('admin.deleteComment');
    // Edit post (AJAX/modal)
    Route::get('/posts/{post}/edit', [AdminController::class, 'editPost'])->name('admin.editPost');
    Route::post('/posts/{post}/edit', [AdminController::class, 'editPost']);
    Route::get('/comments/{comment}/edit', [AdminController::class, 'editComment'])->name('admin.editComment');
    Route::post('/comments/{comment}/edit', [AdminController::class, 'editComment']);
    
});

require __DIR__.'/auth.php';
