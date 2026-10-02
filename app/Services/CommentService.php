<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class CommentService
{
    public function store(Post $post, array $data, int $userId): Comment
    {
        if (!empty($data['parent_id'])) {
            $parent = $post->comments()->find($data['parent_id']);
            if (!$parent) {
                throw new \InvalidArgumentException('Родительский комментарий не найден в этом посте');
            }
        }
        return DB::transaction(function () use ($post, $data, $userId) {
            $comment = $post->comments()->create([
                'user_id' => $userId,
                'content' => $data['content'],
                'parent_id' => $data['parent_id'] ?? null,
            ]);

            return $comment->load('user');
        });
    }

    public function update(Comment $comment, array $data): Comment
    {
        return DB::transaction(function () use ($comment, $data) {
            $comment->update(['content' => $data['content']]);

            // Можно добавить событие
            // event(new CommentUpdatedEvent($comment));

            return $comment;
        });
    }

    public function delete(Comment $comment): void
    {
        DB::transaction(function () use ($comment) {
            $comment->delete();
            // Можно добавить событие
            // event(new CommentDeletedEvent($comment));
        });
    }
}
