<?php

namespace Tests\Feature;

use App\Models\CounselingSession;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_dashboard_shows_own_activity_and_face_status(): void
    {
        $user = User::factory()->create(['name' => 'Nadia', 'face_registered_at' => now()]);
        Post::create(['user_id' => $user->id, 'body' => 'Cerita milik Nadia']);
        CounselingSession::create([
            'user_id' => $user->id,
            'topic' => 'Kecemasan',
            'status' => 'menunggu',
        ]);

        $this->actingAs($user)
            ->get(route('dasbor'))
            ->assertOk()
            ->assertSee('Nadia')
            ->assertSee('Cerita milik Nadia')
            ->assertSee('Kecemasan')
            ->assertSee('Terdaftar');
    }
}
