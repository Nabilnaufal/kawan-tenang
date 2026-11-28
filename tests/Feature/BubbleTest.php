<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BubbleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_publish_anonymous_post_and_comment(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('bubble.store'), [
            'body' => 'Hari ini terasa lebih ringan.',
            'tag' => 'Perlu dukungan',
        ])->assertRedirect();

        $post = Post::firstOrFail();
        $this->assertSame($user->id, $post->user_id);
        $this->assertSame('Hari ini terasa lebih ringan.', $post->body);
        $this->assertNotSame('Hari ini terasa lebih ringan.', $post->getRawOriginal('body'));

        $this->post(route('bubble.komentar', $post), [
            'body' => 'Pelan-pelan, kamu tidak sendirian.',
        ])->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $comment = Comment::firstOrFail();
        $this->assertSame('Pelan-pelan, kamu tidak sendirian.', $comment->body);
        $this->assertNotSame('Pelan-pelan, kamu tidak sendirian.', $comment->getRawOriginal('body'));
    }

    public function test_user_can_toggle_like_and_report_post(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'user_id' => $user->id,
            'body' => 'Cerita anonim.',
        ]);

        $this->actingAs($user);

        $this->postJson(route('bubble.suka', $post))
            ->assertOk()
            ->assertJson(['liked' => true, 'likes' => 1]);

        $this->post(route('bubble.lapor', $post), [
            'reason' => 'Spam atau penipuan',
            'note' => 'Catatan moderator.',
        ])->assertRedirect();

        $this->assertDatabaseHas('reports', [
            'reporter_id' => $user->id,
            'reportable_id' => $post->id,
            'reportable_type' => Post::class,
            'status' => 'baru',
        ]);
    }
}
