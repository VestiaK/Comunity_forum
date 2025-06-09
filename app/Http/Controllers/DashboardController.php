<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        // Get 4 latest posts with their categories
        $latestPosts = Post::with(['category', 'asker'])
            ->latest()
            ->take(4)
            ->get();

        // Get categories for the navigation
        $categories = Category::paginate(20);

        return view('dashboard', compact('latestPosts', 'categories'));
    }
}