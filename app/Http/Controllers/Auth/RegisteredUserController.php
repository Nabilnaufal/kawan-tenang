<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Wajah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.daftar');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nim' => ['required', 'string', 'max:30', 'unique:users,nim'],
            'prodi' => ['required', 'string', 'max:100'],
            'role' => ['required', Rule::in([User::ROLE_PENGGUNA, User::ROLE_KONSELOR])],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'wajah' => ['required', 'json'],
            'liveness' => ['required', 'accepted'],
            'setuju' => ['accepted'],
        ], [
            'nim.unique' => 'NIM ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'wajah.required' => 'Pindai wajah dulu sebelum mendaftar.',
            'wajah.json' => 'Data wajah tidak valid. Pindai ulang.',
            'liveness.required' => 'Selesaikan pemeriksaan liveness dengan membuka mulut.',
            'liveness.accepted' => 'Buka mulut saat diminta sebelum melanjutkan.',
            'setuju.accepted' => 'Setujui kebijakan privasi dan aturan komunitas dulu.',
            'password.confirmed' => 'Ulangi kata sandi tidak sama.',
        ]);

        $user = User::create([
            'name' => $data['nama'],
            'nim' => $data['nim'],
            'prodi' => $data['prodi'],
            'role' => $data['role'],
            'is_active' => true,
            'email' => $data['email'],
            'password' => $data['password'],
            'face_descriptor' => Wajah::deskriptor($data['wajah']),
            'face_registered_at' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice');
    }
}
