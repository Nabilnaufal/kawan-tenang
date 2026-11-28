<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    public function notice(): View
    {
        return view('auth.verifikasi-email');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        /** @var User $user */
        $user = $request->user();

        return redirect()
            ->route($this->homeRoute($user))
            ->with('status', 'Email kamu sudah terverifikasi. Selamat datang di Kawan Tenang.');
    }

    public function send(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route($this->homeRoute($user));
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'Link verifikasi baru sudah dikirim ke email kamu.');
    }

    private function homeRoute(User $user): string
    {
        return match ($user->role) {
            User::ROLE_ADMIN => 'admin.dasbor',
            User::ROLE_KONSELOR => 'konselor.dasbor',
            default => 'dasbor',
        };
    }
}
