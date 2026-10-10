<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $posts = Post::all();
        $users = User::all();

        if ($posts->isEmpty() || $users->isEmpty()) {
            return;
        }

        // Корневые комментарии
        $parents = Comment::factory()
            ->count(50)
            ->state(fn () => [
                'post_id' => $posts->random()->id,
                'user_id' => $users->random()->id,
            ])
            ->create();

        // Ответы на них
        Comment::factory()
            ->count(30)
            ->state(fn () => [
                'post_id' => $posts->random()->id,
                'user_id' => $users->random()->id,
            ])
            ->replyTo($parents->random())
            ->create();
    }
}
