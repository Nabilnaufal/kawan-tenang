<?php

namespace Tests\Feature;

use App\Models\CounselingSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CounselorTest extends TestCase
{
    use RefreshDatabase;

    public function test_counselor_dashboard_lists_assigned_sessions(): void
    {
        $counselor = User::factory()->create(['role' => 'konselor', 'name' => 'Bu Rani']);
        $user = User::factory()->create();
        $session = CounselingSession::create([
            'user_id' => $user->id,
            'counselor_id' => $counselor->id,
            'topic' => 'Tekanan akademik',
            'status' => 'aktif',
        ]);

        $this->actingAs($counselor)
            ->get(route('konselor.dasbor'))
            ->assertOk()
            ->assertSee('Tekanan akademik')
            ->assertSee('Anonim #'.$user->id);

        $this->actingAs($counselor)
            ->get(route('konselor.sesi.show', $session))
            ->assertOk()
            ->assertSee('Tekanan akademik');
    }

    public function test_counselor_can_save_note_and_toggle_availability(): void
    {
        $counselor = User::factory()->create(['role' => 'konselor']);
        $session = CounselingSession::create([
            'user_id' => User::factory()->create()->id,
            'counselor_id' => $counselor->id,
            'topic' => 'Kesepian',
            'status' => 'aktif',
        ]);

        $this->actingAs($counselor)
            ->post(route('konselor.sesi.catatan', $session), ['counselor_note' => 'Tindak lanjut minggu depan.'])
            ->assertRedirect();

        $this->assertSame('Tindak lanjut minggu depan.', $session->fresh()->counselor_note);

        $this->actingAs($counselor)
            ->patch(route('konselor.ketersediaan'), ['is_available' => 0])
            ->assertRedirect();

        $this->assertFalse($counselor->fresh()->is_available);
    }

    public function test_other_counselor_cannot_write_session_note(): void
    {
        $session = CounselingSession::create([
            'user_id' => User::factory()->create()->id,
            'counselor_id' => User::factory()->create(['role' => 'konselor'])->id,
            'topic' => 'Keluarga',
            'status' => 'aktif',
        ]);

        $this->actingAs(User::factory()->create(['role' => 'konselor']))
            ->post(route('konselor.sesi.catatan', $session), ['counselor_note' => 'Tidak boleh'])
            ->assertForbidden();
    }
}
