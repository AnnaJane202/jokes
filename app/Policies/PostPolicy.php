<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Может ли пользователь удалить пост
     */
    public function delete(User $user, Post $post): bool
    {
        // Владелец поста или админ
        return $user->id === $post->user_id || $user->is_admin;
    }

    /**
     * Может ли пользователь редактировать пост
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->is_admin;
    }
}
