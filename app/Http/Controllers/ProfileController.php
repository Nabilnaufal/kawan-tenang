<?php

namespace App\Http\Controllers;

use App\Support\Wajah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        return view('profil', [
            'user' => $request->user(),
            'role' => $request->user()->role,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $emailChanged = $user->email !== $data['email'];
        $user->fill(['name' => $data['name'], 'email' => $data['email']]);

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        if ($emailChanged) {
            $user->sendEmailVerificationNotification();

            return redirect()
                ->route('verification.notice')
                ->with('status', 'Email berubah. Link verifikasi baru sudah dikirim.');
        }

        return back()->with('status', 'Profil berhasil diperbarui.');
    }

    public function registerFace(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'wajah' => ['required', 'json'],
            'liveness' => ['required', 'accepted'],
        ], [
            'wajah.required' => 'Pindai wajah dulu.',
            'wajah.json' => 'Data wajah tidak valid. Pindai ulang.',
            'liveness.required' => 'Selesaikan pemeriksaan liveness dengan membuka mulut.',
            'liveness.accepted' => 'Buka mulut saat diminta sebelum menyimpan wajah.',
        ]);

        $request->user()->update([
            'face_descriptor' => Wajah::deskriptor($data['wajah']),
            'face_registered_at' => now(),
        ]);

        return back()->with('status', 'Data wajah berhasil disimpan. Kamu bisa masuk dengan pengenalan wajah.');
    }

    public function forgetFace(Request $request): RedirectResponse
    {
        $request->user()->update([
            'face_descriptor' => null,
            'face_registered_at' => null,
        ]);

        return back()->with('status', 'Data wajah berhasil dihapus.');
    }
}
