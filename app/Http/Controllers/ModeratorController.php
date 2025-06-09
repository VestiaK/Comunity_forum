<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Post;
use App\Models\Comment;

class ModeratorController extends Controller
{
    public function reports()
    {
        $reports = Report::with(['user', 'reportable'])->latest()->paginate(20);
        return view('moderator.reports', compact('reports'));
    }

    public function closePost(Post $post)
    {
        $post->update(['closed' => true]);
        return back()->with('success', 'Diskusi berhasil ditutup.');
    }

    public function deleteComment(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Komentar berhasil dihapus.');
    }

    public function deletePost(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Thread berhasil dihapus.');
    }

    public function deleteReport($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();
        return back()->with('success', 'Laporan dihapus.');
    }

    public function deleteContent($type, $id)
    {
        if ($type === 'post') {
            $content = Post::findOrFail($id);
        } else {
            $content = Comment::findOrFail($id);
        }
        $content->delete();
        return back()->with('success', 'Konten berhasil dihapus.');
    }
}
