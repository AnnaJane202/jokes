<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Comment\StoreRequest;
use App\Http\Requests\Client\Comment\UpdateRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;

class CommentController extends Controller
{
    protected CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(StoreRequest $request, Post $post)
    {

        $this->commentService->store($post, $request->validated(), auth()->id());

        return back()->with('success', 'Комментарий добавлен');

    }

    public function update(UpdateRequest $request, Post $post, Comment $comment)
    {
        $this->authorize('update', $comment);

        $this->commentService->update($comment, $request->validated());

        return back()->with('success', 'Комментарий обновлён');

    }

    public function destroy(Post $post, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $this->commentService->delete($comment);

        return back()->with('success', 'Комментарий удалён');

    }
}
