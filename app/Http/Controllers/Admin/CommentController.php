<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Post\PostResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $comments = Comment::with(['user', 'post'])
            ->when($request->search, fn($q) => $q->where('content', 'like', "%{$request->search}%"))
            ->when($request->post_id, fn($q) => $q->where('post_id', $request->post_id))
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->latest()
            ->paginate(20);

        $comments->getCollection()->transform(fn($post) => CommentResource::make($post)->resolve());
        return inertia('Admin/Comment/Index', [
            'comments' => $comments,
            'filters' => $request->only(['search', 'post_id', 'user_id']),
        ]);
    }

    public function edit(Comment $comment)
    {
        $comment->load(['user', 'post']);
        return inertia('Admin/Comment/Edit', [
            'comment' => $comment,
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment->update($validated);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Комментарий обновлён');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete(); // soft delete

        return redirect()->route('admin.comments.index')
            ->with('success', 'Комментарий удалён');
    }
}
