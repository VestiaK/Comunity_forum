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
    }  

    public function update(Request $request, Comment $comment)
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
    }   
    /**
     * Hapus komentar.
     */
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $comment->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting comment'
            ], 500);
        }
    }

    /**
     * Vote (upvote/downvote) pada komentar.
     */
public function vote(Request $request, Comment $comment)
{
    $user = auth()->user();

    // Tidak boleh vote komentar sendiri
    if ($user->id === $comment->user_id) {
        return response()->json([
            'success' => false,
            'message' => 'Tidak bisa vote komentar sendiri.'
        ]);
    }

    $type = $request->input('type');
    $point = match ($type) {
        'upvote' => 10,
        'downvote' => -2,
        default => null,
    };

    if ($point === null) {
        return response()->json([
            'success' => false,
            'message' => 'Vote tidak valid.'
        ]);
    }

    // Cek apakah user sudah pernah vote komentar ini
    $existingVote = $user->votedComments()->where('comment_id', $comment->id)->first();

    if ($existingVote) {
        $oldType = $existingVote->pivot->type;
        if ($oldType === $type) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan vote ini.'
            ]);
        }
        // Koreksi reputasi: kurangi reputasi lama, tambah reputasi baru
        $oldPoint = $oldType === 'upvote' ? 10 : -2;
        $comment->reputation_points -= $oldPoint;
        $comment->reputation_points += $point;
        $comment->save();

        $comment->user->reputation_points -= $oldPoint;
        $comment->user->reputation_points += $point;
        $comment->user->save();

        // Update pivot
        $user->votedComments()->updateExistingPivot($comment->id, ['type' => $type]);

        return response()->json([
            'success' => true,
            'message' => 'Vote berhasil diubah!',
            'reputation_points' => $comment->reputation_points,
        ]);
    }

    // Simpan vote baru (pivot table)
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