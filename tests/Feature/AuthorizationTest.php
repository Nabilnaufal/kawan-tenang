<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_private_user_or_counselor_pages(): void
    {
        $this->get(route('dasbor'))->assertRedirect(route('login'));
        $this->get(route('verifikasi'))->assertRedirect(route('login'));
        $this->get(route('konselor.dasbor'))->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_open_counselor_area(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'pengguna']))
            ->get(route('konselor.dasbor'))
            ->assertForbidden();
    }
}
