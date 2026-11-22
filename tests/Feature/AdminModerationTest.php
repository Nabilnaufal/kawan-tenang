<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminModerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admin_can_process_report_and_hide_post(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $owner = User::factory()->create(['role' => 'pengguna']);
        $post = Post::create(['user_id' => $owner->id, 'body' => 'Konten yang dilaporkan.']);
        $report = Report::create([
            'reporter_id' => $owner->id,
            'reportable_id' => $post->id,
            'reportable_type' => Post::class,
            'reason' => 'Spam atau penipuan',
        ]);

        $this->actingAs($owner)->get(route('admin.laporan'))->assertForbidden();

        $this->actingAs($admin)->patch(route('admin.laporan.update', $report), [
            'status' => 'ditindaklanjuti',
            'action' => 'sembunyikan',
        ])->assertRedirect();

        $this->assertDatabaseHas('reports', ['id' => $report->id, 'status' => 'ditindaklanjuti']);
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'is_hidden' => 1]);
    }
}
