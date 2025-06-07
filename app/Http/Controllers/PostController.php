<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Tampilkan daftar post.
     */
    public function index(Request $request)
    {
        $query = Post::query()->with(['category', 'asker']);

        // Filter berdasarkan asker
        if ($request->has('asker')) {
            $user = User::where('username', $request->asker)->first();
            if ($user) {
                $query->where('user_id', $user->id);
            } else {
                abort(404, 'Asker tidak ditemukan');
            }
        }

        // Filter berdasarkan kategori
        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            } else {
                abort(404, 'Category tidak ditemukan');
            }
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%$search%")
                  ->orWhere('body', 'ILIKE', "%$search%")
                  ->orWhereHas('asker', function ($q2) use ($search) {
                      $q2->where('name', 'ILIKE', "%$search%")
                         ->orWhere('username', 'ILIKE', "%$search%")
                         ->orWhere('email', 'ILIKE', "%$search%");
                  });
            });
        }

        $posts = $query->latest()->paginate(20);
        $categories = Category::all();

        // Judul dinamis
        $title = 'Semua Diskusi';
        if ($request->has('asker') && isset($user)) {
            $title = 'Diskusi oleh ' . $user->name;
        } elseif ($request->has('category') && isset($category)) {
            $title = 'Diskusi Kategori ' . $category->name;
        }

        return view('posts', compact('posts', 'title', 'categories'));
    }

    /**
     * Tampilkan form pembuatan post baru.
     */
    public function create()
    {
        return view('posts-create');
    }

    /**
     * Simpan post baru ke database.
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::random(16);
        Post::create($validated);

        return response()->json(['success' => true]);
    }

    /**
     * Tampilkan detail post.
     */
    public function show(Post $post)
    {
        return view('post', ['post' => $post]);
    }

    /**
     * Tampilkan form edit post.
     */
    public function edit(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }
        return response()->json($post);
    }

    /**
     * Update post di database.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }
        $validated = $request->validated();
        $post->update($validated);

        return response()->json(['success' => true]);
    }

    /**
     * Hapus post dari database.
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }
        $post->delete();

        return response()->json(['success' => true]);
    }
}
