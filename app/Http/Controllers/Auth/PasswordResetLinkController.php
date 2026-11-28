<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.lupa-sandi');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(['email' => $data['email']]);

        if ($status === Password::RESET_THROTTLED) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Permintaan terlalu sering. Coba lagi beberapa saat lagi.']);
        }

        return back()->with('status', 'Jika email terdaftar, link reset kata sandi sudah dikirim.');
    }
}
