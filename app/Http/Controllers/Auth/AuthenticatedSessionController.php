<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Wajah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.masuk');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi salah.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route($this->homeRoute(Auth::user())));
    }

    public function faceLogin(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'wajah' => ['required', 'json'],
            'liveness' => ['required', 'accepted'],
        ], [
            'wajah.required' => 'Pindai wajah dulu.',
            'wajah.json' => 'Data wajah tidak valid. Pindai ulang.',
            'liveness.required' => 'Selesaikan pemeriksaan liveness dengan membuka mulut.',
            'liveness.accepted' => 'Buka mulut saat diminta sebelum melanjutkan.',
        ]);

        $wajah = Wajah::deskriptor($data['wajah']);

        $terdekat = User::query()
            ->whereNotNull('face_descriptor')
            ->get()
            ->map(fn (User $user) => [$user, Wajah::jarak($user->face_descriptor, $wajah)])
            ->sortBy(fn (array $baris) => $baris[1])
            ->first();

        if (! $terdekat || $terdekat[1] > 0.5) {
            throw ValidationException::withMessages([
                'wajah' => 'Wajah tidak dikenali. Coba lagi dengan pencahayaan yang lebih terang.',
            ]);
        }

        Auth::login($terdekat[0]);
        $request->session()->regenerate();

        return redirect()->intended(route($this->homeRoute($terdekat[0])));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('masuk');
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
