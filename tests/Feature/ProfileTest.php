<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_profile_and_remove_face_data(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'face_descriptor' => array_fill(0, 128, 0.1),
            'face_registered_at' => now(),
        ]);

        $this->actingAs($user)
            ->patch(route('profil.update'), [
                'name' => 'Nama Baru',
                'email' => $user->email,
            ])
            ->assertRedirect();

        $this->assertSame('Nama Baru', $user->fresh()->name);

        $this->actingAs($user)
            ->delete(route('profil.wajah.destroy'))
            ->assertRedirect();

        $this->assertNull($user->fresh()->face_descriptor);
        $this->assertNull($user->fresh()->face_registered_at);
    }

    public function test_changing_email_requires_new_verification(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch(route('profil.update'), [
                'name' => $user->name,
                'email' => 'baru@example.com',
            ])
            ->assertRedirect(route('verification.notice'));

        $this->assertNull($user->fresh()->email_verified_at);
        Notification::assertSentTo($user->fresh(), VerifyEmailNotification::class);
    }

    public function test_counselor_can_register_face_from_profile(): void
    {
        $counselor = User::factory()->create(['role' => User::ROLE_KONSELOR]);
        $descriptor = array_fill(0, 128, 0.1);

        $this->actingAs($counselor)
            ->patch(route('profil.wajah.update'), [
                'wajah' => json_encode($descriptor),
                'liveness' => '1',
            ])
            ->assertRedirect();

        $saved = $counselor->fresh();
        $this->assertSame($descriptor, $saved->face_descriptor);
        $this->assertNotNull($saved->face_registered_at);
    }
}
