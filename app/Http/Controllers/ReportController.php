<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class ReportController extends Controller
{
    public function store(Request $request, $type, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);
        $model = $type === 'post' ? Post::class : Comment::class;
        $item = $model::findOrFail($id);
        $item->reports()->create([
            'user_id' => auth()->id(),
            'reason' => $request->reason,
        ]);
        return response()->json(['success' => true]);
    }
}
