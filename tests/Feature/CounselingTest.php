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
        $counselor = User::factory()->create(['role' => 'konselor']);
        $this->actingAs($user);

        $response = $this->post(route('konseling.store'), [
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
