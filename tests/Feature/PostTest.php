<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    // ==============================
    // Создание поста
    // ==============================

    // 1. Гость не может создать пост
    public function test_guest_cannot_create_post(): void
    {
        $response = $this->postJson('/posts', [
            'title'   => 'Hello',
            'content' => 'Content',
        ]);

        $response->assertUnauthorized(); // 401
        $this->assertDatabaseCount('posts', 0);
    }

    // 2. Авторизованный пользователь может создать пост
    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/posts', [
                'title'       => 'Hello world',
                'content'     => 'Some content here',
                'description' => 'Short description',
                'published'   => 1,
                'category_id' => $category->id,
            ]);

        $response->assertOk();

        $this->assertDatabaseHas('posts', [
            'title'       => 'Hello world',
            'user_id'     => $user->id,
            'category_id' => $category->id,
            'published'   => 1,
        ]);
    }

    // 3. Валидация: title обязателен
    public function test_title_is_required(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/posts', [
                'content'     => 'Content',
                'published'   => 1,
                'category_id' => $category->id,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
        $this->assertDatabaseCount('posts', 0);
    }

    // 4. Валидация: content обязателен
    public function test_content_is_required(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/posts', [
                'title'       => 'Hello',
                'published'   => 1,
                'category_id' => $category->id,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('content');
        $this->assertDatabaseCount('posts', 0);
    }

    // 5. Slug генерируется автоматически
    public function test_slug_is_generated_on_create(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($user)->postJson('/posts', [
            'title'       => 'Hello world',
            'content'     => 'Content',
            'published'   => 1,
            'category_id' => $category->id,
        ]);

        $post = Post::first();

        $this->assertNotNull($post->slug);
        $this->assertSame(10, strlen($post->slug));
    }

    // ==============================
    // Безопасность
    // ==============================

    // 6. Нельзя создать пост от имени другого пользователя
    public function test_user_cannot_create_post_on_behalf_of_another_user(): void
    {
        $author   = User::factory()->create();
        $attacker = User::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($attacker)->postJson('/posts', [
            'title'       => 'Hacked',
            'content'     => 'Content',
            'published'   => 1,
            'user_id'     => $author->id,   // ← пытаемся выдать себя за другого
            'category_id' => $category->id,
        ]);

        // Пост должен быть от имени атакующего, а не жертвы
        $this->assertDatabaseHas('posts', [
            'title'   => 'Hacked',
            'user_id' => $attacker->id,
        ]);

        $this->assertDatabaseMissing('posts', [
            'title'   => 'Hacked',
            'user_id' => $author->id,
        ]);
    }

    // ==============================
    // Просмотр
    // ==============================

    // 7. Index возвращает 200
    public function test_index_returns_ok(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/');

        $response->assertOk();
    }

    // 8. Show отдаёт конкретный пост
    public function test_show_displays_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['published' => 1]);

        $response = $this
            ->actingAs($user)
            ->get("/posts/{$post->id}");

        $response->assertOk();
    }
}
