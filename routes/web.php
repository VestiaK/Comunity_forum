<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Requests\LoginRequest;
use App\Models\Post;
use App\Models\Category;
use app\Models\User;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;

Route::get('/', function () {
    $categories = Category::all();
    return view('welcome', compact('categories'));
});


Route::get('/dashboard', function () {
    $categories = Category::all();
    return view('dashboard', compact('categories'));
})->name('dashboard');

Route::resource('posts', PostController::class);
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
Route::get('/profile', function ()
{
    $categories = Category::all();
    return view('profile', ['title' => 'Profile', 'categories' => $categories]);
});
Route::get('/reputation', function ()
{
    $categories = Category::all();
    return view('reputation', ['title' => 'Reputation', 'categories' => $categories]);
});


// Comment routes
Route::middleware(['auth'])->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::match(['put', 'post'], '/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/vote', [CommentController::class, 'vote']);
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

require __DIR__.'/auth.php';
