<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'post_id' => Post::factory(),
            'user_id' => User::factory(),
            'parent_id' => null,
            'content' => $this->faker->paragraph(rand(1, 3)),
        ];
    }

    /**
     * Комментарий с конкретным постом
     */
    public function forPost(Post $post): static
    {
        return $this->state(fn () => [
            'post_id' => $post->id,
        ]);
    }

    /**
     * Комментарий от конкретного пользователя
     */
    public function fromUser(User $user): static
    {
        return $this->state(fn () => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Ответ на другой комментарий
     */
    public function replyTo(Comment $parent): static
    {
        return $this->state(fn () => [
            'parent_id' => $parent->id,
            'post_id' => $parent->post_id,
        ]);
    }
}
