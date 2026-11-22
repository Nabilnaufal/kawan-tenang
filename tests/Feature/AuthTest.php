<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function wajah(float $nilai): array
    {
        return array_fill(0, 128, $nilai);
    }

    public function test_pendaftaran_menyimpan_profil_dan_wajah_lalu_login(): void
    {
        $response = $this->post('/daftar', [
            'nama' => 'Nadia Putri',
            'nim' => '2024001',
            'prodi' => 'Bimbingan Konseling',
            'role' => 'pengguna',
            'email' => 'nadia@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'wajah' => json_encode($this->wajah(0.25)),
            'setuju' => '1',
        ]);

        $response->assertRedirect(route('dasbor'));
        $this->assertAuthenticated();

        $user = User::where('email', 'nadia@example.com')->firstOrFail();
        $this->assertSame('2024001', $user->nim);
        $this->assertCount(128, $user->face_descriptor);
        $this->assertNotNull($user->face_registered_at);
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

        $this->post('/masuk/wajah', ['wajah' => json_encode($this->wajah(0.251))])
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

        $this->post('/masuk/wajah', ['wajah' => json_encode($this->wajah(5))])
            ->assertSessionHasErrors('wajah');

        $this->assertGuest();
    }
}
