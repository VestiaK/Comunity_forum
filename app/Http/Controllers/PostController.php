<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\User;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::query()->with(['category', 'asker']);
        if ($request->has('asker')) {
            $user = User::where('username', $request->asker)->first();
            if ($user) {
                $query->where('user_id', $user->id);
            } else {
                abort(404, 'Asker tidak ditemukan');
            }
        }
        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            } else {
                abort(404, 'Category tidak ditemukan');
            }
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%$search%")
                  ->orWhere('body', 'ILIKE', "%$search%")
                  ->orWhereHas('asker', function($q2) use ($search) {
                      $q2->where('name', 'ILIKE', "%$search%")
                         ->orWhere('username', 'ILIKE', "%$search%")
                         ->orWhere('email', 'ILIKE', "%$search%") ;
                  });
            });
        }
        $posts = $query->latest()->paginate(20);
        $title = 'Semua Diskusi';
        $categories = Category::all();
        if ($request->has('asker') && isset($user)) {
            $title = 'Diskusi oleh ' . $user->name;
        } elseif ($request->has('category') && isset($category)) {
            $title = 'Diskusi Kategori ' . $category->name;
        }
        return view('posts', compact('posts', 'title', 'categories'));
    }

    /**
     * Show the form for creating a new resource.s
     */
    public function create()
    {
        // Tampilkan form create post
        return view('posts-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        Post::create($validated);
        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Hanya tampilkan, tidak perlu authorize
        return view('post', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if ($post->user_id !== auth()->id()) abort(403);
        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        if ($post->user_id !== auth()->id()) abort(403);
        $validated = $request->validated();
        $post->update($validated);
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) abort(403);
        $post->delete();
        return response()->json(['success' => true]);
    }
}
