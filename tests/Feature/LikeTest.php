<?php

namespace Tests\Feature;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_like_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->postJson("/posts/{$post->id}/like");

        $response->assertUnauthorized();   // 401 от middleware auth
        $this->assertDatabaseCount('likes', 0);
    }

    public function test_authenticated_user_can_like_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->postJson("/posts/{$post->id}/like");

        $response->assertOk();
        $response->assertJson([
            'is_liked'     => true,
            'likes_count'  => 1,
        ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_user_cannot_like_post_twice(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)->postJson("/posts/{$post->id}/like");

        $response = $this->actingAs($user)->postJson("/posts/{$post->id}/like");

        $response->assertStatus(422);
        $this->assertDatabaseCount('likes', 1);   // дубль не создался
    }

    public function test_cannot_like_nonexistent_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/posts/999999/like');

        $response->assertNotFound();   // 404 от route model binding
        $this->assertDatabaseCount('likes', 0);
    }

    public function test_user_can_unlike_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        Like::create(['user_id' => $user->id, 'post_id' => $post->id]);

        $response = $this->actingAs($user)->postJson("/posts/{$post->id}/unlike");

        $response->assertOk();
        $response->assertJson([
            'is_liked'    => false,
            'likes_count' => 0,
        ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_unlike_without_existing_like_does_not_fail(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->postJson("/posts/{$post->id}/unlike");

        $response->assertOk();
        $this->assertDatabaseCount('likes', 0);
    }

    public function test_toggle_likes_post_when_not_liked(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->postJson("/posts/{$post->id}/toggle-like");

        $response->assertOk();
        $response->assertJson([
            'is_liked'    => true,
            'likes_count' => 1,
        ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_toggle_unlikes_post_when_already_liked(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        Like::create(['user_id' => $user->id, 'post_id' => $post->id]);

        $response = $this->actingAs($user)->postJson("/posts/{$post->id}/toggle-like");

        $response->assertOk();
        $response->assertJson([
            'is_liked'    => false,
            'likes_count' => 0,
        ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_toggle_three_times_does_not_create_duplicates(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // лайк → анлайк → лайк
        $this->actingAs($user)->postJson("/posts/{$post->id}/toggle-like");
        $this->actingAs($user)->postJson("/posts/{$post->id}/toggle-like");
        $this->actingAs($user)->postJson("/posts/{$post->id}/toggle-like");

        $this->assertDatabaseCount('likes', 1);
    }

    public function test_user_id_is_taken_from_auth_not_from_request(): void
    {
        $author   = User::factory()->create();
        $attacker = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($attacker)->postJson("/posts/{$post->id}/like", [
            'user_id' => $author->id,   // пытаемся лайкнуть от имени жертвы
        ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $attacker->id,
            'post_id' => $post->id,
        ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $author->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_unique_index_prevents_double_like_in_db(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        Like::create(['user_id' => $user->id, 'post_id' => $post->id]);

        // Прямая попытка вставить дубль в обход контроллера
        $this->expectException(QueryException::class);

        Like::create(['user_id' => $user->id, 'post_id' => $post->id]);
    }
}
