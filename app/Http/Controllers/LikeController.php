<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;

class LikeController extends Controller
{
    public function like(Post $post)
    {
        $userId = auth()->id();

        // Проверяем, не лайкнул ли уже пользователь
        $existingLike = Like::where('user_id', $userId)
            ->where('post_id', $post->id)
            ->first();

        if ($existingLike) {
            return response()->json([
                'message' => 'Вы уже лайкнули этот пост',
                'likes_count' => $post->likes_count
            ], 422);
        }

        // Создаем лайк
        Like::create([
            'user_id' => $userId,
            'post_id' => $post->id
        ]);

        // Обновляем данные поста
        $post->loadCount('likes');

        return response()->json([
            'message' => 'Пост лайкнут',
            'likes_count' => $post->likes_count,
            'is_liked' => true
        ]);
    }

    public function unlike(Post $post)
    {
        $userId = auth()->id();

        // Находим и удаляем лайк
        Like::where('user_id', $userId)
            ->where('post_id', $post->id)
            ->delete();

        // Обновляем данные поста
        $post->loadCount('likes');

        return response()->json([
            'message' => 'Лайк удален',
            'likes_count' => $post->likes_count,
            'is_liked' => false
        ]);
    }

    public function toggleLike(Post $post)
    {
        $userId = auth()->id();
        if ($userId) {
            $existingLike = Like::where('user_id', $userId)
                ->where('post_id', $post->id)
                ->first();

            if ($existingLike) {
                $existingLike->delete();
                $isLiked = false;
            } else {
                Like::create([
                    'user_id' => $userId,
                    'post_id' => $post->id
                ]);
                $isLiked = true;
            }
        } else {
            $isLiked = false;
        }


        $post->loadCount('likes');

        return response()->json([
            'likes_count' => $post->likes_count,
            'is_liked' => $isLiked
        ]);
    }

    public function getLikes(Post $post)
    {
        $likes = $post->likes()->with('user')->get();

        return response()->json([
            'likes' => $likes,
            'count' => $post->likes_count
        ]);
    }
}
