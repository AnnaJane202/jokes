<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    // 1. Гость не может создать комментарий
    public function test_guest_cannot_create_comment(): void
    {
        $post = Post::factory()->create();
        $response = $this->post("/posts/{$post->id}/comments", [
            'content' => 'test',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('comments', 0);
    }

    // 2. Авторизованный может создать комментарий
    public function test_authenticated_user_can_create_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from("/posts/{$post->id}")
            ->post("/posts/{$post->id}/comments", [
                'content' => 'test',
            ]);

        $response->assertRedirect("/posts/{$post->id}");
        $this->assertDatabaseHas('comments', [
            'content' => 'test',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_user_id_is_taken_from_auth_not_from_request(): void
    {
        $author   = User::factory()->create();
        $attacker = User::factory()->create();
        $post = Post::factory()->for($author)->create();

        $this
            ->actingAs($attacker)
            ->from("/posts/{$post->id}")
            ->post("/posts/{$post->id}/comments", [
                'content' => 'test',
                'user_id' => $author->id,   // ← пытаемся выдать себя за другого
            ]);

        $this->assertDatabaseHas('comments', [
            'content' => 'test',
            'user_id' => $attacker->id,   // ← должно быть attacker
        ]);

        $this->assertDatabaseMissing('comments', [
            'content' => 'test',
            'user_id' => $author->id,
        ]);
    }

    // 3. Валидация: content обязателен
    public function test_content_is_required(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from("/posts/{$post->id}")
            ->post("/posts/{$post->id}/comments", [
                'content' => '',
            ]);

        $response->assertRedirect("/posts/{$post->id}");
        $response->assertSessionHasErrors('content');
        $this->assertDatabaseCount('comments', 0);
    }

    // 4. Несуществующий пост
    public function test_cannot_create_comment_to_nonexistent_post(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post("/posts/99999/comments", [
                'content' => 'test',
            ]);

        $response->assertNotFound();
        $this->assertDatabaseCount('comments', 0);

    }

    // 5. Комментарий привязывается к правильному
    public function test_comment_is_attached_to_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)
            ->from("/posts/{$post->id}")
            ->post("/posts/{$post->id}/comments", [
                'content' => 'test',
            ]);

        $comment = \App\Models\Comment::first();

        $this->assertDatabaseHas('comments', [
            'id'      => $comment->id,
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }

    // 6. Ответ на комментарий (parent_id) сохраняется корректно
    public function test_reply_to_comment_is_saved_correctly(): void
    {
        $user = User::factory()->create();
        $answerer = User::factory()->create();
        $post = Post::factory()->create();

        // создаём родительский комментарий
        $this->actingAs($user)
            ->from("/posts/{$post->id}")
            ->post("/posts/{$post->id}/comments", [
                'content' => 'parent',
            ]);

        $commentParent = \App\Models\Comment::where('content', 'parent')->firstOrFail();

        // создаём ответ
        $this->actingAs($answerer)
            ->from("/posts/{$post->id}")
            ->post("/posts/{$post->id}/comments", [
                'content'   => 'son',
                'parent_id' => $commentParent->id,
            ]);

        // проверяем родителя
        $this->assertDatabaseHas('comments', [
            'content' => 'parent',
            'user_id' => $user->id,
            'post_id' => $post->id,
            'parent_id' => null,
        ]);

        // проверяем ответ
        $this->assertDatabaseHas('comments', [
            'content'   => 'son',
            'parent_id' => $commentParent->id,
            'user_id'   => $answerer->id,
            'post_id'   => $post->id,
        ]);
    }

    // 7. Юзер может удалить свой комментарий
    public function test_user_can_delete_his_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)
            ->from("/posts/{$post->id}")
            ->post("/posts/{$post->id}/comments", [
                'content' => 'test',
            ]);

        $comment = \App\Models\Comment::where('content', 'test')->firstOrFail();

        $response = $this->actingAs($user)
            ->from("/posts/{$post->id}")
            ->delete("/posts/{$post->id}/comments/{$comment->id}");

        $response->assertRedirect("/posts/{$post->id}");
        $this->assertSoftDeleted('comments', ['id' => $comment->id]);
    }

    // 8. Пользователь не может удалить чужой коммент
    public function test_user_cannot_delete_others_comment(): void
    {
        $author = User::factory()->create();
        $intruder = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($author)
            ->from("/posts/{$post->id}")
            ->post("/posts/{$post->id}/comments", [
                'content' => 'author comment'
            ]);

        $comment = Comment::where('content', 'author comment')->firstOrFail();

        $response = $this->actingAs($intruder)
            ->from("/posts/{$post->id}")
            ->delete("/posts/{$post->id}/comments/{$comment->id}");

        $response->assertForbidden();
        $this->assertNotSoftDeleted('comments', ['id' => $comment->id]);
    }

    // 9. Пользователь не может редактировать чужой коммент
    public function test_user_cannot_update_others_comment(): void
    {
        $author   = User::factory()->create();
        $intruder = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($author)
            ->from("/posts/{$post->id}")
            ->post("/posts/{$post->id}/comments", ['content' => 'original']);

        $comment = Comment::where('content', 'original')->firstOrFail();

        $response = $this->actingAs($intruder)
            ->from("/posts/{$post->id}")
            ->put("/posts/{$post->id}/comments/{$comment->id}", [
                'content' => 'hacked',
            ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'content' => 'original']);
    }

    // 10.
    public function test_cannot_reply_to_comment_from_another_post(): void
    {
        $user = User::factory()->create();

        $postA = Post::factory()->create();
        $postB = Post::factory()->create();

        // в посте A создаём родительский комментарий
        $this->actingAs($user)
            ->from("/posts/{$postA->id}")
            ->post("/posts/{$postA->id}/comments", ['content' => 'in A']);

        $commentInA = Comment::where('content', 'in A')->firstOrFail();

        // пытаемся ответить на него, но отправляем запрос в пост B
        $response = $this->actingAs($user)
            ->from("/posts/{$postB->id}")
            ->post("/posts/{$postB->id}/comments", [
                'content'   => 'cross-post reply',
                'parent_id' => $commentInA->id,   // ← родитель из чужого поста
            ]);

        // Правильно: либо 422, либо родитель отброшен
        $this->assertDatabaseMissing('comments', [
            'content'   => 'cross-post reply',
            'parent_id' => $commentInA->id,
        ]);
    }

    public function test_cannot_update_comment_via_wrong_post_url(): void
    {
        $user = User::factory()->create();

        $postA = Post::factory()->create();
        $postB = Post::factory()->create();

        // в посте A создаём комментарий
        $this->actingAs($user)
            ->from("/posts/{$postA->id}")
            ->post("/posts/{$postA->id}/comments", ['content' => 'original']);

        $commentInA = Comment::where('content', 'original')->firstOrFail();

        // пытаемся обновить его через URL с постом B
        $response = $this->actingAs($user)
            ->from("/posts/{$postB->id}")
            ->put("/posts/{$postB->id}/comments/{$commentInA->id}", [
                'content' => 'hacked',
            ]);

        // комментарий должен быть недоступен через этот URL
        $response->assertNotFound();  // 404 — правильный ответ
        $this->assertDatabaseHas('comments', [
            'id'      => $commentInA->id,
            'content' => 'original',   // ← текст НЕ изменился
        ]);
    }

    public function test_mass_assignment_vulnerability(): void
    {
        $attacker = User::factory()->create();

        // пытаемся создать пост с чужим user_id
        $this->actingAs($attacker)->postJson('/posts', [
            'title'       => 'Hacked',
            'content'     => 'X',
            'published'   => 1,
            'category_id' => Category::factory()->create()->id,
            'user_id'     => 999999,   // чужой id
        ]);

        // если unguard включён — user_id запишется
        $this->assertDatabaseMissing('posts', [
            'title'   => 'Hacked',
            'user_id' => 999999,
        ]);
    }




}
