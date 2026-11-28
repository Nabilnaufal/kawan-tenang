<?php

namespace Tests\Feature;

use App\Models\CounselingSession;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CounselingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_session_and_send_private_message(): void
    {
        $user = User::factory()->create(['role' => 'pengguna']);
        User::factory()->create(['role' => 'konselor', 'name' => 'Konselor lain']);
        $counselor = User::factory()->create(['role' => 'konselor', 'name' => 'Konselor pilihan']);
        $this->actingAs($user);

        $response = $this->post(route('konseling.store'), [
            'counselor_id' => $counselor->id,
            'topic' => 'Kecemasan',
            'note' => 'Saya ingin bercerita.',
        ]);

        $session = CounselingSession::firstOrFail();
        $response->assertRedirect(route('konseling.show', $session));
        $this->assertSame($counselor->id, $session->counselor_id);
        $this->assertSame('aktif', $session->status);
        $this->get(route('konseling.index'))->assertOk();
        $this->get(route('konseling.show', $session))->assertOk();

        $this->post(route('konseling.message', $session), ['body' => 'Halo, saya ingin mulai bercerita.'])
            ->assertRedirect();

        $this->assertDatabaseHas('messages', [
            'counseling_session_id' => $session->id,
            'sender_id' => $user->id,
        ]);

        $message = Message::firstOrFail();
        $this->assertSame('Halo, saya ingin mulai bercerita.', $message->body);
        $this->assertNotSame('Halo, saya ingin mulai bercerita.', $message->getRawOriginal('body'));
    }

    public function test_user_cannot_request_session_with_unavailable_counselor(): void
    {
        $user = User::factory()->create(['role' => 'pengguna']);
        $counselor = User::factory()->create(['role' => 'konselor', 'is_available' => false]);

        $this->actingAs($user)
            ->from(route('konseling.index'))
            ->post(route('konseling.store'), [
                'counselor_id' => $counselor->id,
                'topic' => 'Kesepian',
            ])
            ->assertRedirect(route('konseling.index'))
            ->assertSessionHasErrors('counselor_id');

        $this->assertDatabaseCount('counseling_sessions', 0);
    }

    public function test_participants_can_poll_and_send_messages_without_a_reload(): void
    {
        $user = User::factory()->create();
        $counselor = User::factory()->create(['role' => 'konselor']);
        $session = CounselingSession::create([
            'user_id' => $user->id,
            'counselor_id' => $counselor->id,
            'topic' => 'Kecemasan',
            'status' => 'aktif',
        ]);

        $this->actingAs($user)
            ->postJson(route('konseling.message', $session), ['body' => 'Aku butuh teman bicara.'])
            ->assertOk()
            ->assertJsonPath('count', 1);

        $message = Message::firstOrFail();
        $this->assertSame('Aku butuh teman bicara.', $message->body);
        $this->assertNotSame('Aku butuh teman bicara.', $message->getRawOriginal('body'));

        $response = $this->actingAs($counselor)
            ->getJson(route('konseling.messages', $session))
            ->assertOk()
            ->assertJsonPath('count', 1);

        $this->assertStringContainsString('Aku butuh teman bicara.', $response->json('html'));
        $this->assertNotNull($message->fresh()->read_at);
    }

    public function test_outsider_cannot_poll_session_messages(): void
    {
        $session = CounselingSession::create([
            'user_id' => User::factory()->create()->id,
            'topic' => 'Kesepian',
            'status' => 'menunggu',
        ]);

        $this->actingAs(User::factory()->create())
            ->getJson(route('konseling.messages', $session))
            ->assertForbidden();
    }

    public function test_non_participant_cannot_read_or_send_session_message(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $session = CounselingSession::create([
            'user_id' => $owner->id,
            'topic' => 'Tekanan akademik',
            'status' => 'menunggu',
        ]);

        $this->actingAs($stranger);
        $this->get(route('konseling.show', $session))->assertForbidden();
        $this->post(route('konseling.message', $session), ['body' => 'Tidak boleh'])
            ->assertForbidden();
    }
}
