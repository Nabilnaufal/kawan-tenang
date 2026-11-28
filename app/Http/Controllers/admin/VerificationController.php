<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function index(): View
    {
        $users = User::query()->orderByDesc('face_registered_at')->paginate(20);

        return view('admin.verifikasi', [
            'users' => $users,
            'counts' => [
                'terdaftar' => User::query()->whereNotNull('face_registered_at')->count(),
                'belum' => User::query()->whereNull('face_registered_at')->count(),
            ],
        ]);
    }

    public function reset(Request $request, User $user): RedirectResponse
    {
        abort_if($user->is($request->user()), 403, 'Tidak bisa menghapus verifikasi sendiri.');

        $user->update(['face_descriptor' => null, 'face_registered_at' => null]);

        return back()->with('status', 'Data wajah pengguna dihapus.');
    }
}
