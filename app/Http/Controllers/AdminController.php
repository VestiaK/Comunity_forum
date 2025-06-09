<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    public function deleteUser(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        // Hapus semua post milik user
        $user->posts()->delete();
        $user->delete();
        return back()->with('success', 'User dan semua post miliknya berhasil dihapus.');
    }

    public function deleteCategory(Category $category)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        // Hapus semua post yang terkait kategori ini
        $category->posts()->delete();
        $category->delete();
        return back()->with('success', 'Kategori dan semua post di dalamnya berhasil dihapus.');
    }
    public function deletePost(Post $post)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        // Hapus semua komentar yang terkait post ini
        $post->comments()->delete();
        $post->delete();
        return back()->with('success', 'Post dan semua komentar di dalamnya berhasil dihapus.');
    }

    public function users()
    {
        $users = \App\Models\User::paginate(20);
        return view('admin.users', compact('users'));
    }

    public function categories()
    {
        $categoriesp = Category::paginate(20);
        return view('admin.categories', compact('categoriesp'));
    }
    public function posts()
    {
        $posts = \App\Models\Post::with(['category', 'user'])->paginate(20);
        return view('admin.posts', compact('posts'));
    }
    public function comments()
    {
        $comments = \App\Models\Comment::with(['post', 'user'])->paginate(20);
        return view('admin.comments', compact('comments'));
    }

    public function deleteComment(\App\Models\Comment $comment)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        $comment->delete();
        return back()->with('success', 'Komentar berhasil dihapus.');
    }

    public function editPost(Request $request, Post $post)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name' => 'required',
                'category_id' => 'required|exists:categories,id',
                'body' => 'required',
            ]);
            $post->update($validated);
            return response()->json(['success' => true, 'message' => 'Post berhasil diupdate.']);
        }
        // Untuk GET, return data post sebagai JSON untuk diisi ke modal
        return response()->json($post->only(['id', 'name', 'category_id', 'body']));
    }

    public function editComment(Request $request, \App\Models\Comment $comment)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'body' => 'required',
            ]);
            $comment->update($validated);
            return response()->json(['success' => true, 'message' => 'Komentar berhasil diupdate.']);
        }
        // Untuk GET, return data comment sebagai JSON untuk diisi ke modal
        return response()->json($comment->only(['id', 'body']));
    }
}
