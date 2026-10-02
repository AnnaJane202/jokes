<?php

namespace App\Services;


use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostService
{
    public static function store(array $data): Post
    {
        return Post::create($data);
    }

    public static function update(Post $post, array $data): Post
    {
        $post->update($data);
        return $post->fresh();
    }

    public static function deletePostWithLikes(Post $post): bool
    {
        return DB::transaction(function () use ($post) {
            try {
                DB::table('likes')->where('post_id', $post->id)->delete();

                return $post->delete();

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }
}
