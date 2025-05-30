<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Requests\LoginRequest;
use App\Models\Post;
use App\Models\Category;
use app\Models\User;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdatePasswordRequest;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('posts', PostController::class)->middleware('auth');

Route::get('/profile', function ()
{
    return view('profile', ['title' => 'Profile']);
});
Route::get('/reputation', function ()
{
    return view('reputation', ['title' => 'Reputation']);
});

Route::get('/posts', [PostController::class, 'index']);
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
