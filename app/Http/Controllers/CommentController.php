<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment = $post->comments()->create([
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function edit(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json($comment);
    }    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        try {
            $comment->update($validated);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating comment'], 500);
        }
    }    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $comment->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting comment'], 500);
        }
    }

    public function vote(Request $request, Comment $comment)
    {
        $user = auth()->user();
        // Cegah user vote lebih dari sekali pada comment yang sama
        if ($user->id === $comment->user_id) {
            return response()->json(['success' => false, 'message' => 'Tidak bisa vote komentar sendiri.']);
        }
        if ($user->votedComments()->where('comment_id', $comment->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Anda sudah vote komentar ini.']);
        }

        $type = $request->input('type');
        $point = 0;
        if ($type === 'upvote') {
            $point = 10;
        } elseif ($type === 'downvote') {
            $point = -2;
        } else {
            return response()->json(['success' => false, 'message' => 'Vote tidak valid.']);
        }

        // Simpan vote (pivot table)
        $user->votedComments()->attach($comment->id, ['type' => $type]);

        // Update reputasi comment
        $comment->reputation_points += $point;
        $comment->save();

        // Update reputasi user pemilik komentar
        $comment->user->reputation_points += $point;
        $comment->user->save();

        return response()->json([
            'success' => true,
            'message' => $type === 'upvote' ? 'Upvote berhasil!' : 'Downvote berhasil!',
            'reputation_points' => $comment->reputation_points,
        ]);
    }
}
