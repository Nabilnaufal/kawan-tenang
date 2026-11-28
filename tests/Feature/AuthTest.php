<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function wajah(float $nilai): array
    {
        return array_fill(0, 128, $nilai);
    }

    public function test_pendaftaran_menyimpan_profil_dan_wajah_lalu_mengirim_verifikasi_email(): void
    {
        Notification::fake();

        $response = $this->post('/daftar', [
            'nama' => 'Nadia Putri',
            'nim' => '2024001',
            'prodi' => 'Bimbingan Konseling',
            'role' => 'pengguna',
            'email' => 'nadia@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'wajah' => json_encode($this->wajah(0.25)),
            'liveness' => '1',
            'setuju' => '1',
        ]);

        $response->assertRedirect(route('verification.notice'));
        $this->assertAuthenticated();

        $user = User::where('email', 'nadia@example.com')->firstOrFail();
        $this->assertSame('2024001', $user->nim);
        $this->assertCount(128, $user->face_descriptor);
        $this->assertNotNull($user->face_registered_at);
        $this->assertNull($user->email_verified_at);
        $this->assertTrue($user->is_active);
        Notification::assertSentTo($user, VerifyEmailNotification::class);
        $this->assertSame('bcrypt', Hash::info($user->getRawOriginal('password'))['algoName']);
        $this->assertTrue(Hash::check('rahasia123', $user->getRawOriginal('password')));
    }

    public function test_unverified_user_must_verify_email_before_opening_dashboard(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get(route('dasbor'))
            ->assertRedirect(route('verification.notice'));

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)],
        );

        $this->actingAs($user)
            ->get($verificationUrl)
            ->assertRedirect(route('dasbor'));

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_login_wajah_mencocokkan_descriptor_terdekat(): void
    {
        $user = User::create([
            'name' => 'Nadia',
            'email' => 'nadia@example.com',
            'password' => 'rahasia123',
            'face_descriptor' => $this->wajah(0.25),
        ]);

        User::create([
            'name' => 'Orang Lain',
            'email' => 'lain@example.com',
            'password' => 'rahasia123',
            'face_descriptor' => $this->wajah(0.9),
        ]);

        $this->post('/masuk/wajah', ['wajah' => json_encode($this->wajah(0.251)), 'liveness' => '1'])
            ->assertRedirect(route('dasbor'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_wajah_menolak_descriptor_tak_dikenal(): void
    {
        User::create([
            'name' => 'Nadia',
            'email' => 'nadia@example.com',
            'password' => 'rahasia123',
            'face_descriptor' => $this->wajah(0.25),
        ]);

        $this->post('/masuk/wajah', ['wajah' => json_encode($this->wajah(5)), 'liveness' => '1'])
            ->assertSessionHasErrors('wajah');

        $this->assertGuest();
    }

    public function test_login_wajah_menolak_request_tanpa_liveness(): void
    {
        User::create([
            'name' => 'Nadia',
            'email' => 'nadia@example.com',
            'password' => 'rahasia123',
            'face_descriptor' => $this->wajah(0.25),
        ]);

        $this->post('/masuk/wajah', ['wajah' => json_encode($this->wajah(0.25))])
            ->assertSessionHasErrors('liveness');

        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login_with_password_or_face(): void
    {
        $user = User::factory()->unverified()->create([
            'is_active' => false,
            'face_descriptor' => $this->wajah(0.25),
        ]);

        $this->post(route('masuk.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->post(route('masuk.wajah'), [
            'wajah' => json_encode($this->wajah(0.25)),
            'liveness' => '1',
        ])->assertSessionHasErrors('wajah');

        $this->assertGuest();
    }
}
