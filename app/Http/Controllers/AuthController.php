<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function formMasuk(): View
    {
        return view('auth.masuk');
    }

    public function masuk(Request $request): RedirectResponse
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

        return redirect()->intended(route($this->beranda(Auth::user())));
    }

    public function formDaftar(): View
    {
        return view('auth.daftar');
    }

    public function daftar(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nim' => ['required', 'string', 'max:30', 'unique:users,nim'],
            'prodi' => ['required', 'string', 'max:100'],
            'role' => ['required', 'in:pengguna,konselor'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'wajah' => ['required', 'json'],
            'setuju' => ['accepted'],
        ], [
            'nim.unique' => 'NIM ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'wajah.required' => 'Pindai wajah dulu sebelum mendaftar.',
            'wajah.json' => 'Data wajah tidak valid. Pindai ulang.',
            'setuju.accepted' => 'Setujui kebijakan privasi dan aturan komunitas dulu.',
            'password.confirmed' => 'Ulangi kata sandi tidak sama.',
        ]);

        $wajah = $this->deskriptor($data['wajah']);

        $user = User::create([
            'name' => $data['nama'],
            'nim' => $data['nim'],
            'prodi' => $data['prodi'],
            'role' => $data['role'],
            'email' => $data['email'],
            'password' => $data['password'],
            'face_descriptor' => $wajah,
            'face_registered_at' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($this->beranda($user));
    }

    public function masukWajah(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'wajah' => ['required', 'json'],
        ], [
            'wajah.required' => 'Pindai wajah dulu.',
            'wajah.json' => 'Data wajah tidak valid. Pindai ulang.',
        ]);

        $wajah = $this->deskriptor($data['wajah']);

        // shortcut: pencocokan 1:N ke semua descriptor; ganti ke pencarian vektor
        // (mis. pgvector/faiss) saat jumlah pengguna sudah besar.
        $terdekat = User::whereNotNull('face_descriptor')
            ->get()
            ->map(fn (User $user) => [$user, $this->jarak($user->face_descriptor, $wajah)])
            ->sortBy(fn (array $baris) => $baris[1])
            ->first();

        if (! $terdekat || $terdekat[1] > 0.5) {
            throw ValidationException::withMessages([
                'wajah' => 'Wajah tidak dikenali. Coba lagi dengan pencahayaan yang lebih terang.',
            ]);
        }

        Auth::login($terdekat[0]);
        $request->session()->regenerate();

        return redirect()->intended(route($this->beranda($terdekat[0])));
    }

    public function keluar(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('masuk');
    }

    private function beranda(User $user): string
    {
        return match ($user->role) {
            'admin' => 'admin.dasbor',
            'konselor' => 'konselor.dasbor',
            default => 'dasbor',
        };
    }

    /** Validasi isi descriptor lalu ubah ke 128 angka. */
    private function deskriptor(string $json): array
    {
        $wajah = json_decode($json, true);

        if (! is_array($wajah) || count($wajah) !== 128 || count(array_filter($wajah, 'is_numeric')) !== 128) {
            throw ValidationException::withMessages([
                'wajah' => 'Data wajah tidak valid. Pindai ulang.',
            ]);
        }

        return array_map('floatval', $wajah);
    }

    /** Jarak Euclidean antara dua descriptor wajah 128 dimensi. */
    private function jarak(array $a, array $b): float
    {
        if (count($a) !== count($b)) {
            return INF;
        }

        $jumlah = 0.0;

        foreach ($a as $i => $nilai) {
            $jumlah += ($nilai - $b[$i]) ** 2;
        }

        return sqrt($jumlah);
    }
}
