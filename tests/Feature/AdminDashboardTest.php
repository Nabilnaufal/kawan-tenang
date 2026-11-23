<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_and_user_management_use_real_accounts(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Admin Satu']);
        $member = User::factory()->create(['role' => 'pengguna', 'name' => 'Anggota Dua', 'face_registered_at' => now()]);

        $this->actingAs($admin)->get(route('admin.dasbor'))->assertOk()->assertSee('Admin Satu');

        $this->actingAs($admin)
            ->get(route('admin.pengguna', ['role' => 'pengguna']))
            ->assertOk()
            ->assertSee('Anggota Dua');

        $this->actingAs($admin)
            ->patch(route('admin.pengguna.peran', $member), ['role' => 'konselor'])
            ->assertRedirect();

        $this->assertSame('konselor', $member->fresh()->role);
    }

    public function test_admin_cannot_change_own_role_or_reset_own_face(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'face_registered_at' => now()]);

        $this->actingAs($admin)
            ->patch(route('admin.pengguna.peran', $admin), ['role' => 'pengguna'])
            ->assertForbidden();

        $this->actingAs($admin)
            ->delete(route('admin.verifikasi.reset', $admin))
            ->assertForbidden();

        $this->assertSame('admin', $admin->fresh()->role);
        $this->assertNotNull($admin->fresh()->face_registered_at);
    }

    public function test_admin_can_reset_another_users_face(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = User::factory()->create(['face_registered_at' => now()]);

        $this->actingAs($admin)
            ->delete(route('admin.verifikasi.reset', $member))
            ->assertRedirect();

        $this->assertNull($member->fresh()->face_registered_at);
        $this->assertNull($member->fresh()->face_descriptor);
    }
}
