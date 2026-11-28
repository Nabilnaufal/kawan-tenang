<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_a_password_reset_link(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->post(route('lupa-sandi.store'), ['email' => $user->email])
            ->assertRedirect()
            ->assertSessionHas('status', 'Jika email terdaftar, link reset kata sandi sudah dikirim.');

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function test_user_can_set_a_new_password_with_a_valid_token(): void
    {
        $user = User::factory()->create(['password' => 'password-lama']);
        $token = Password::broker()->createToken($user);

        $this->get(route('password.reset', ['token' => $token, 'email' => $user->email]))
            ->assertOk()
            ->assertSee('Buat kata sandi baru');

        $this->post(route('password.store'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password-baru',
            'password_confirmation' => 'password-baru',
        ])->assertRedirect(route('masuk'));

        $this->assertTrue(Hash::check('password-baru', $user->fresh()->password));
    }
}
